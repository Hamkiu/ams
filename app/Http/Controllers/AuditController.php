<?php

namespace App\Http\Controllers;

use App\Models\AuditGroups;
use App\Models\AuditGroupsMembers;
use App\Models\AuditAnswers;
use App\Models\AuditAnswerChecklists;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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

        return view('audit.show', compact('group', 'answers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
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
        ]);

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
        $answer->checklists()->delete();
        if ($request->filled('checklist_id')) {

            foreach ($request->checklist_id as $checklistId) {

                AuditAnswerChecklists::create([

                    'audit_answer_id'   => $answer->id,

                    'audit_checklist_id'=> $checklistId,

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
}
