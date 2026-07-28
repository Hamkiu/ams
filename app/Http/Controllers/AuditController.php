<?php

namespace App\Http\Controllers;

use App\Models\AuditGroups;
use App\Models\AuditGroupsMembers;
use App\Models\AuditAnswers;
use App\Models\AuditAnswerChecklists;
use App\Models\AuditFiles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
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

        return view('audit.show', compact('group', 'answers'));
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
        $answerId = decode($request->audit_answer_id);

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

        foreach ($request->file('tfiles') as $file) {

            $extension = strtolower($file->getClientOriginalExtension());

            if (!in_array($extension, $allowed)) {

                return response()->json([
                    'success' => false,
                    'message' => 'Jenis fail tidak dibenarkan.'
                ], 422);
            }

            if ($file->getSize() > 10485760) {

                return response()->json([
                    'success' => false,
                    'message' => 'Saiz fail melebihi 10MB.'
                ], 422);
            }

            $filename = $file->getClientOriginalName();

            $newName = uniqid('AUDIT_');

            $path = $file->storeAs(
                'uploads/audit/' . $answerId,
                $newName . '.' . $extension,
                'public'
            );

            AuditFiles::create([

                'ref_id' => $answerId,
                'file_name_ori' => $filename,
                'file_name' => $newName,
                'file_path' => $path,
                'file_ext' => $extension,

            ]);
        }

        auditTrail(
            'Upload',
            'Audit',
            'Attachment',
            $answerId,
            'Attachment',
            \Auth::user()->id
        );

        return response()->json([
            'success' => true,
            'message' => 'Lampiran berjaya dimuat naik.'
        ]);
    }

    public function listattachment(Request $request)
    {
        $tfiles = AuditFiles::where('ref_id', decode($request->answer))->get();

        $datatable = Datatables::of($tfiles)
            ->addIndexColumn()
            ->addColumn('file_name', function ($row) {
                return $row->file_name_ori;
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at->format('d/m/Y H:i:s');
            })
            ->addColumn('tindakan', function ($row) {
                $btn = '';
                $btn .= ' <a href="' . route('auditfiles.download', encode($row->id)) . '" class="btn btn-success btn-sm" title="Download"><i class="material-icons-outlined">download</i></a>';
                $btn .= ' <a href="' . route('auditfiles.delete', encode($row->id)) . '" class="btn btn-danger btn-sm" title="Delete"><i class="material-icons-outlined">delete</i></a>';
                return $btn;
            })
            ->rawColumns(['file_name', 'created_at', 'tindakan'])
            ->make(true);

        return $datatable;
    }

    public function download($id)
    {
        $tfiles = AuditFiles::find(decode($id));
        $pathToFile = storage_path('app/public/' . $tfiles->file_path);
        auditTrail('Download', 'Audit', 'Attachment', $tfiles->ref_id, $tfiles->file_name_ori, \Auth::user()->id);
        return response()->download($pathToFile, $tfiles->file_name_ori);
    }

    public function delete($id)
    {
        $tfiles = AuditFiles::find(decode($id));
        $tfiles->delete();

        auditTrail('Delete', 'Audit', 'Attachment', $tfiles->ref_id, $tfiles->file_name_ori, \Auth::user()->id);

        return redirect()
            ->back()
            ->with('success', 'Lampiran berjaya dihapus.');
    }
}
