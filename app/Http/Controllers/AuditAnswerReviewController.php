<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditAnswers;
use App\Models\AuditGroupsMembers;
use App\Models\AuditAnswerReviews;
use App\Models\AuditAnswerReviewChecklists;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AuditAnswerReviewController extends Controller
{
    public function edit($id)
    {
        $answer = AuditAnswers::with([
            'auditGroup',
            'auditItem.checklists',
            'checklists',
            'auditor',
            'review.checklists',
        ])->findOrFail(decode($id));

        /*
        |--------------------------------------------------------------------------
        | PASTIKAN USER ADALAH KETUA KUMPULAN
        |--------------------------------------------------------------------------
        */
        $member = AuditGroupsMembers::where(
            'audit_group_id',
            $answer->audit_group_id
        )
            ->where('user_id', Auth::id())
            ->where('role', 'Leader')
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | HANYA BOLEH PINDA SEMASA MENUNGGU KESIMPULAN
        |--------------------------------------------------------------------------
        */
        if ($answer->auditGroup->status !== 'MENUNGGU KESIMPULAN') {
            abort(403, 'Jawapan audit tidak lagi boleh dipinda.');
        }


        return view(
            'audit.summary.review',
            compact('answer')
        );
    }

    public function store(Request $request)
    {
        /*
    |--------------------------------------------------------------------------
    | VALIDATION ASAS
    |--------------------------------------------------------------------------
    */
        $request->validate(
            [
                'audit_answer_id' => 'required',
                'penemuan_lain' => 'required|string',
                'bukti_audit' => 'required|string',

                'checklist_status' => 'nullable|array',
                'checklist_status.*' => 'required|in:AKUR,TIDAK AKUR,TIDAK BERKAITAN',
            ],
            [
                'audit_answer_id.required' => 'Jawapan audit tidak ditemukan.',

                'penemuan_lain.required' => 'Penemuan lain wajib diisi.',

                'bukti_audit.required' => 'Bukti audit wajib diisi.',

                'checklist_status.array' => 'Format checklist tidak sah.',

                'checklist_status.*.required' =>
                'Semua checklist wajib dijawab.',

                'checklist_status.*.in' =>
                'Status checklist tidak sah.',
            ]
        );


        /*
    |--------------------------------------------------------------------------
    | DECODE AUDIT ANSWER ID
    |--------------------------------------------------------------------------
    */
        try {

            $answerId = decode($request->audit_answer_id);
        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->withErrors([
                    'audit_answer_id' => 'Jawapan audit tidak sah.'
                ]);
        }


        /*
    |--------------------------------------------------------------------------
    | DAPATKAN JAWAPAN ASAL AUDITOR
    |--------------------------------------------------------------------------
    */
        $answer = AuditAnswers::with([
            'auditGroup',
            'auditItem.checklists',
            'checklists',
            'review',
        ])->findOrFail($answerId);


        /*
    |--------------------------------------------------------------------------
    | PASTIKAN USER SEMASA ADALAH KETUA KUMPULAN
    |--------------------------------------------------------------------------
    */
        $leader = AuditGroupsMembers::where(
            'audit_group_id',
            $answer->audit_group_id
        )
            ->where('user_id', Auth::id())
            ->where('role', 'Leader')
            ->first();


        if (!$leader) {

            abort(
                403,
                'Anda tidak mempunyai kebenaran untuk meminda jawapan audit ini.'
            );
        }


        /*
    |--------------------------------------------------------------------------
    | PASTIKAN STATUS GROUP MASIH MENUNGGU KESIMPULAN
    |--------------------------------------------------------------------------
    |
    | Selepas Ketua hantar rumusan:
    |
    | MENUNGGU KESIMPULAN
    |          ↓
    | MENUNGGU ULASAN
    |
    | Maka pindaan tidak lagi dibenarkan.
    |
    */
        if ($answer->auditGroup->status !== 'MENUNGGU KESIMPULAN') {

            return back()->withErrors([
                'error' =>
                'Jawapan audit tidak lagi boleh dipinda kerana rumusan telah dihantar.'
            ]);
        }


        /*
    |--------------------------------------------------------------------------
    | DAPATKAN CHECKLIST YANG SEPATUTNYA UNTUK ITEM
    |--------------------------------------------------------------------------
    */
        $requiredChecklistIds = $answer
            ->auditItem
            ->checklists
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->sort()
            ->values();


        /*
    |--------------------------------------------------------------------------
    | DAPATKAN CHECKLIST YANG DIHANTAR
    |--------------------------------------------------------------------------
    */
        $submittedChecklistIds = collect(
            array_keys($request->checklist_status ?? [])
        )
            ->map(fn($id) => (string) $id)
            ->sort()
            ->values();


        /*
    |--------------------------------------------------------------------------
    | PASTIKAN SEMUA CHECKLIST YANG BETUL DIJAWAB
    |--------------------------------------------------------------------------
    |
    | Contoh:
    |
    | Checklist item sebenar:
    | 10, 11, 12
    |
    | Request:
    | 10, 11, 12    = OK
    |
    | Request:
    | 10, 11        = GAGAL
    |
    | Request:
    | 10, 11, 99    = GAGAL
    |
    */
        if (
            $requiredChecklistIds->toArray()
            !==
            $submittedChecklistIds->toArray()
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'checklist_status' =>
                    'Sila pilih Akur, Tidak Akur atau Tidak Berkaitan bagi semua checklist.'
                ]);
        }


        /*
    |--------------------------------------------------------------------------
    | DATABASE TRANSACTION
    |--------------------------------------------------------------------------
    */
        DB::beginTransaction();

        try {

            /*
        |--------------------------------------------------------------------------
        | SEMAK REVIEW SEDIA ADA
        |--------------------------------------------------------------------------
        */
            $review = AuditAnswerReviews::where(
                'audit_answer_id',
                $answer->id
            )->first();


            /*
        |--------------------------------------------------------------------------
        | CREATE REVIEW
        |--------------------------------------------------------------------------
        */
            if (!$review) {

                $review = new AuditAnswerReviews();

                $review->audit_answer_id = $answer->id;

                $review->created_by = Auth::id();
            }


            /*
        |--------------------------------------------------------------------------
        | UPDATE DATA REVIEW
        |--------------------------------------------------------------------------
        */
            $review->penemuan_lain = $request->penemuan_lain;

            $review->bukti_audit = $request->bukti_audit;

            $review->updated_by = Auth::id();

            $review->save();


            /*
        |--------------------------------------------------------------------------
        | DELETE CHECKLIST REVIEW LAMA
        |--------------------------------------------------------------------------
        |
        | Kita recreate checklist setiap kali Ketua simpan.
        |
        | Jawapan asal auditor dalam audit_answer_checklists
        | TIDAK disentuh.
        |
        */
            $review->checklists()->delete();


            /*
        |--------------------------------------------------------------------------
        | SIMPAN CHECKLIST REVIEW
        |--------------------------------------------------------------------------
        */
            foreach (
                $request->checklist_status ?? []
                as $checklistId => $status
            ) {

                AuditAnswerReviewChecklists::create([
                    'audit_answer_review_id' => $review->id,
                    'audit_checklist_id' => $checklistId,
                    'status' => $status,
                ]);
            }


            /*
        |--------------------------------------------------------------------------
        | AUDIT TRAIL
        |--------------------------------------------------------------------------
        */
            auditTrail(
                'Update',
                'Audit',
                'Pindaan Jawapan Audit',
                $review->id,
                $answer->auditItem->perkara,
                Auth::id()
            );


            /*
        |--------------------------------------------------------------------------
        | COMMIT
        |--------------------------------------------------------------------------
        */
            DB::commit();


            /*
        |--------------------------------------------------------------------------
        | RETURN KE SUMMARY
        |--------------------------------------------------------------------------
        */
            return redirect()
                ->route(
                    'audit.summary',
                    encode($answer->audit_group_id)
                )
                ->with(
                    'success',
                    'Pindaan jawapan audit telah berjaya disimpan.'
                );
        } catch (\Throwable $e) {

            /*
        |--------------------------------------------------------------------------
        | ROLLBACK
        |--------------------------------------------------------------------------
        */
            DB::rollBack();


            /*
        |--------------------------------------------------------------------------
        | LOG ERROR
        |--------------------------------------------------------------------------
        */
            \Log::error(
                'Gagal menyimpan pindaan jawapan audit.',
                [
                    'audit_answer_id' => $answer->id,
                    'user_id' => Auth::id(),
                    'error' => $e->getMessage(),
                ]
            );


            /*
        |--------------------------------------------------------------------------
        | RETURN ERROR
        |--------------------------------------------------------------------------
        */
            return back()
                ->withInput()
                ->withErrors([
                    'error' =>
                    'Pindaan jawapan audit gagal disimpan. Sila cuba semula.'
                ]);
        }
    }
}
