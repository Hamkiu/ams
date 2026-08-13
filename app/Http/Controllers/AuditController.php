<?php

namespace App\Http\Controllers;

use App\Models\AuditGroups;
use App\Models\AuditGroupsMembers;
use App\Models\AuditAnswers;
use App\Models\AuditAnswerChecklists;
use App\Models\AuditFiles;
use App\Models\AuditGroupConclusion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class AuditController extends Controller
{
    public function index()
    {

        $groups = AuditGroupsMembers::with([
            'auditGroup.auditTemplate',
            'auditGroup.members.pengguna'
        ])
            ->where('user_id', Auth::id())
            ->get();

        return view('audit.index', compact('groups'));
    }

    public function show($id)
    {
        $group = AuditGroups::with([
            'auditTemplate.items.checklists',
            'members.pengguna'
        ])->find(decode($id));

        $answers = AuditAnswers::where('audit_group_id', $group->id)
            ->where('user_id', Auth::id())
            ->get()
            ->keyBy('audit_item_id');

        $member = $group->members()
            ->where('user_id', \Auth::user()->id)
            ->firstOrFail();

        return view('audit.show', compact('group', 'answers', 'member'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'audit_group_id' => 'required|exists:audit_groups,id',
                'audit_item_id' => 'required|exists:audit_template_items,id',
                'penemuan_lain' => 'required|string',
                'bukti_audit' => 'required|string',
            ],
            [
                'audit_group_id.required' => 'Audit group wajib diisi',
                'audit_group_id.exists' => 'Audit group tidak ditemukan',
                'audit_item_id.required' => 'Audit item wajib diisi',
                'audit_item_id.exists' => 'Audit item tidak ditemukan',
                'penemuan_lain.required' => 'Penemuan lain wajib diisi',
                'bukti_audit.required' => 'Bukti audit wajib diisi',
            ]
        );

        $answer = AuditAnswers::firstOrNew([
            'audit_group_id' => $request->audit_group_id,
            'audit_item_id'  => $request->audit_item_id,
            'user_id'        => \Auth::user()->id,
        ]);

        $isNew = !$answer->exists;

        if ($isNew) {
            $answer->created_by = \Auth::user()->id;
        }

        $answer->penemuan_lain = $request->penemuan_lain;
        $answer->bukti_audit = $request->bukti_audit;
        $answer->save();

        //status audit group members
        $member = AuditGroupsMembers::where('audit_group_id', $request->audit_group_id)
            ->where('user_id', \Auth::user()->id)
            ->first();

        if ($member && $member->status == 'BELUM BERMULA') {

            $member->status = 'DALAM PROSES';

            $member->started_at = now();

            $member->save();
        }

        //status audit group
        $group = AuditGroups::find($request->audit_group_id);

        if ($group && $group->status == 'BELUM BERMULA') {

            $group->status = 'DALAM PROSES';

            $group->started_at = now();

            $group->save();
        }

        $answer->checklists()->delete();

        if ($request->filled('checklist_id')) {

            foreach ($request->checklist_id as $checklistId) {

                AuditAnswerChecklists::create([

                    'audit_answer_id'   => $answer->id,

                    'audit_checklist_id' => $checklistId,

                ]);
            }
        }

        if ($isNew) {

            auditTrail(
                'Create',
                'Audit',
                'Audit Answer',
                $answer->id,
                $answer->auditItem->perkara,
                \Auth::user()->id
            );

            $message = 'Item (' . $answer->auditItem->perkara . ') telah berjaya disimpan.';
        } else {

            auditTrail(
                'Update',
                'Audit',
                'Audit Answer',
                $answer->id,
                $answer->auditItem->perkara,
                \Auth::user()->id
            );

            $message = 'Item (' . $answer->auditItem->perkara . ') telah berjaya dikemaskini.';
        }

        return back()->with('success', $message);
    }

    public function attachment(Request $request)
    {
        $request->validate([
            'ref_id' => 'required',
            'ref_type' => 'required|in:answer,conclusion',
        ]);

        $refId = decode($request->ref_id);
        $refType = $request->ref_type;

        /*
        |--------------------------------------------------------------------------
        | Pastikan reference wujud
        |--------------------------------------------------------------------------
        */
        if ($refType === 'answer') {

            AuditAnswers::findOrFail($refId);
        } elseif ($refType === 'conclusion') {

            AuditGroupConclusion::findOrFail($refId);
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan fail dipilih
        |--------------------------------------------------------------------------
        */
        if (!$request->hasFile('tfiles')) {

            return response()->json([
                'success' => false,
                'message' => 'Tiada fail dipilih.'
            ], 422);
        }

        $allowed = [
            'doc',
            'docx',
            'pdf',
            'txt',
            'jpeg',
            'png',
            'jpg',
            'gif',
            'svg'
        ];

        /*
        |--------------------------------------------------------------------------
        | Upload File
        |--------------------------------------------------------------------------
        */
        foreach ($request->file('tfiles') as $file) {

            $extension = strtolower(
                $file->getClientOriginalExtension()
            );

            /*
            |--------------------------------------------------------------------------
            | Semak extension
            |--------------------------------------------------------------------------
            */
            if (!in_array($extension, $allowed)) {

                return response()->json([
                    'success' => false,
                    'message' => 'Jenis fail tidak dibenarkan.'
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Maximum 10MB
            |--------------------------------------------------------------------------
            */
            if ($file->getSize() > 10485760) {

                return response()->json([
                    'success' => false,
                    'message' => 'Saiz fail melebihi 10MB.'
                ], 422);
            }

            $filename = $file->getClientOriginalName();

            $newName = uniqid('AUDIT_');

            /*
            |--------------------------------------------------------------------------
            | Folder berasingan mengikut reference
            |--------------------------------------------------------------------------
            |
            | uploads/audit/answer/1/
            | uploads/audit/conclusion/1/
            |
            */
            $path = $file->storeAs(
                'uploads/audit/' . $refType . '/' . $refId,
                $newName . '.' . $extension,
                'public'
            );

            /*
            |--------------------------------------------------------------------------
            | Simpan maklumat file
            |--------------------------------------------------------------------------
            */
            AuditFiles::create([
                'ref_id' => $refId,
                'ref_type' => $refType,
                'file_name_ori' => $filename,
                'file_name' => $newName,
                'file_path' => $path,
                'file_ext' => $extension,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */
        auditTrail(
            'Upload',
            'Audit',
            'Attachment',
            $refId,
            ucfirst($refType) . ' Attachment',
            \Auth::user()->id
        );

        return response()->json([
            'success' => true,
            'message' => 'Lampiran berjaya dimuat naik.'
        ]);
    }

    public function listattachment(Request $request)
    {
        $request->validate([
            'ref_id' => 'required',
            'ref_type' => 'required|in:answer,conclusion',
        ]);

        $refId = decode($request->ref_id);
        $refType = $request->ref_type;

        $tfiles = AuditFiles::where('ref_id', $refId)
            ->where('ref_type', $refType)
            ->get();

        $datatable = Datatables::of($tfiles)
            ->addIndexColumn()

            ->addColumn('file_name', function ($row) {
                return $row->file_name_ori;
            })

            ->addColumn('created_at', function ($row) {
                return $row->created_at->format('d/m/Y H:i:s');
            })

            ->addColumn('tindakan', function ($row) use ($refType) {

                $btn = '';

                // Download sentiasa dibenarkan
                $btn .= ' <a href="' . route('auditfiles.download', encode($row->id)) . '"
                            class="btn btn-success btn-sm"
                            title="Download">
                            <i class="material-icons-outlined">download</i>
                          </a>';

                /*
                |--------------------------------------------------------------------------
                | Attachment Jawapan Auditor
                |--------------------------------------------------------------------------
                */
                if ($refType === 'answer') {

                    $answer = AuditAnswers::with('member')
                        ->find($row->ref_id);

                    if ($answer && !$answer->member->isCompleted()) {

                        $btn .= ' <a href="' . route('auditfiles.delete', encode($row->id)) . '"
                                    class="btn btn-danger btn-sm"
                                    title="Delete">
                                    <i class="material-icons-outlined">delete</i>
                                  </a>';
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Attachment Rumusan Ketua
                |--------------------------------------------------------------------------
                */ elseif ($refType === 'conclusion') {

                    $conclusion = AuditGroupConclusion::find($row->ref_id);

                    // Belum submit rumusan = masih boleh delete
                    if ($conclusion && is_null($conclusion->submitted_at)) {

                        $btn .= ' <a href="' . route('auditfiles.delete', encode($row->id)) . '"
                                    class="btn btn-danger btn-sm"
                                    title="Delete">
                                    <i class="material-icons-outlined">delete</i>
                                  </a>';
                    }
                }

                return $btn;
            })

            ->rawColumns([
                'file_name',
                'created_at',
                'tindakan'
            ])

            ->make(true);

        return $datatable;
    }

    public function download($id)
    {
        $tfiles = AuditFiles::findOrFail(decode($id));

        $pathToFile = storage_path(
            'app/public/' . $tfiles->file_path
        );

        auditTrail(
            'Download',
            'Audit',
            'Attachment',
            $tfiles->ref_id,
            $tfiles->file_name_ori,
            \Auth::user()->id
        );

        return response()->download(
            $pathToFile,
            $tfiles->file_name_ori
        );
    }

    public function delete($id)
    {
        $tfiles = AuditFiles::findOrFail(decode($id));

        $refId = $tfiles->ref_id;
        $fileName = $tfiles->file_name_ori;

        // Delete physical file
        if (Storage::disk('public')->exists($tfiles->file_path)) {
            Storage::disk('public')->delete($tfiles->file_path);
        }

        // Delete database record
        $tfiles->delete();

        auditTrail(
            'Delete',
            'Audit',
            'Attachment',
            $refId,
            $fileName,
            \Auth::user()->id
        );

        return redirect()
            ->back()
            ->with('success', 'Lampiran berjaya dihapus.');
    }

    public function submit(Request $request)
    {
        $groupId = decode($request->audit_group_id);

        $group = AuditGroups::findOrFail($groupId);

        $member = $group->members()
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Bilangan item template
        $totalItem = $group->auditTemplate->items()->count();

        // Semua jawapan auditor
        $answers = AuditAnswers::with([
            'auditItem.checklists',
            'checklists'
        ])
            ->where('audit_group_id', $groupId)
            ->where('created_by', auth()->id())
            ->get();

        // Bilangan jawapan yang lengkap
        $completed = $answers
            ->filter(fn($answer) => $answer->isCompleted())
            ->count();

        // Semak semua item telah lengkap
        if ($completed < $totalItem) {

            return response()->json([
                'success' => false,
                'message' => 'Masih terdapat item audit yang belum lengkap.'
            ], 422);
        }

        // Update status auditor
        $member->status = 'SELESAI';
        $member->completed_at = now();
        $member->save();

        // Jika semua ahli kumpulan selesai
        $allCompleted = $group->members()
            ->where('status', '!=', 'SELESAI')
            ->doesntExist();

        if ($allCompleted) {

            $group->status = 'MENUNGGU KESIMPULAN';

            // Jangan set completed_at lagi
            $group->save();
        }

        auditTrail(
            'Submit',
            'Audit',
            'Audit',
            $groupId,
            'Audit berjaya dihantar',
            auth()->id()
        );

        return response()->json([
            'success' => true,
            'message' => 'Audit berjaya dihantar.'
        ]);
    }

    public function summary($id)
    {
        $groupId = decode($id);

        // Pastikan user yang login memang ahli group ini
        $currentMember = AuditGroupsMembers::where('audit_group_id', $groupId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Hanya Ketua Juruaudit dibenarkan
        if ($currentMember->role !== 'Leader') {
            abort(403, 'Hanya Ketua Juruaudit dibenarkan membuat rumusan audit.');
        }

        // Ambil Audit Group beserta semua data berkaitan
        $auditGroup = AuditGroups::with([
            'auditTemplate.items.checklists',
            'members.pengguna',
            'answers.auditor',
            'answers.checklists',
            'conclusion', // TAMBAH INI
        ])->findOrFail($groupId);

        // Rumusan hanya boleh dibuat selepas semua auditor selesai
        if ($auditGroup->status !== 'MENUNGGU KESIMPULAN') {
            return redirect()
                ->route('audit')
                ->with('error', 'Rumusan audit hanya boleh dibuat selepas semua juruaudit selesai.');
        }

        return view('audit.summary', compact(
            'auditGroup',
            'currentMember'
        ));
    }

    public function storeConclusion(Request $request)
    {
        /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */
        $request->validate([
            'audit_group_id' => 'required',
            'conclusion' => 'required|string',
        ], [
            'audit_group_id.required' => 'Audit group tidak ditemukan.',
            'conclusion.required' => 'Rumusan / kesimpulan wajib diisi.',
        ]);

        $groupId = decode($request->audit_group_id);

        /*
    |--------------------------------------------------------------------------
    | Audit Group
    |--------------------------------------------------------------------------
    */
        $auditGroup = AuditGroups::findOrFail($groupId);

        /*
    |--------------------------------------------------------------------------
    | Pastikan user adalah Ketua Juruaudit
    |--------------------------------------------------------------------------
    */
        $currentMember = AuditGroupsMembers::where('audit_group_id', $groupId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($currentMember->role !== 'Leader') {
            abort(403, 'Hanya Ketua Juruaudit dibenarkan membuat rumusan audit.');
        }

        /*
    |--------------------------------------------------------------------------
    | Pastikan group berada pada status yang betul
    |--------------------------------------------------------------------------
    */
        if ($auditGroup->status !== 'MENUNGGU KESIMPULAN') {

            return redirect()
                ->route('audit')
                ->with(
                    'error',
                    'Rumusan audit hanya boleh dibuat selepas semua juruaudit selesai.'
                );
        }

        /*
    |--------------------------------------------------------------------------
    | Simpan / Update Rumusan
    |--------------------------------------------------------------------------
    */
        $conclusion = AuditGroupConclusion::updateOrCreate(
            [
                'audit_group_id' => $groupId,
            ],
            [
                'conclusion' => $request->conclusion,
                'updated_by' => auth()->id(),
            ]
        );

        /*
    |--------------------------------------------------------------------------
    | Set created_by untuk record baru
    |--------------------------------------------------------------------------
    */
        if ($conclusion->wasRecentlyCreated) {

            $conclusion->created_by = auth()->id();
            $conclusion->save();
        }

        /*
    |--------------------------------------------------------------------------
    | Audit Trail
    |--------------------------------------------------------------------------
    */
        auditTrail(
            $conclusion->wasRecentlyCreated ? 'Create' : 'Update',
            'Audit',
            'Conclusion',
            $conclusion->id,
            'Rumusan / Kesimpulan Ketua Juruaudit',
            auth()->id()
        );

        /*
    |--------------------------------------------------------------------------
    | Redirect balik ke Summary
    |--------------------------------------------------------------------------
    */
        return redirect()
            ->route('audit.summary', encode($groupId))
            ->with('success', 'Rumusan / kesimpulan berjaya disimpan.');
    }
}
