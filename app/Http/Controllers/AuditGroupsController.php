<?php

namespace App\Http\Controllers;

use App\Models\AuditGroups;
use App\Models\AuditGroupsMembers;
use App\Models\AuditTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AuditGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        return view('auditgroup.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $auditTemplates = AuditTemplate::orderBy('name', 'ASC')->where('status', 'PUBLISHED')->get();
        return view('auditgroup.create', compact('auditTemplates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'template' => 'required|string',
            'jabatan' => 'required|string',
        ], [
            'name.required' => 'Nama group wajib diisi',
            'template.required' => 'Template wajib dipilih',
            'jabatan.required' => 'Jabatan wajib diisi',
        ]);
        $groupId = generateId('AG', 'audit_groups', 'id');
        $auditGroup = AuditGroups::create([
            'id' => $groupId,
            'audit_template_id' => $request->template,
            'name' => strtoupper($request->name),
            'jabatan' => $request->jabatan,
            'tarikh' => $request->tarikh,
            'created_by' => \Auth::user()->id,
        ]);
        auditTrail('Create', 'Tetapan Audit', 'Audit Group', $auditGroup->id, $auditGroup->name, \Auth::user()->id);
        return redirect()->route('auditgroup.edit', encode($auditGroup->id))->with('success', 'Group berjaya disimpan');
    }

    public function list(Request $request)
    {
        $data = AuditGroups::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('audit_template_id', function ($row) {
                return $row->auditTemplate->name;
            })
            ->addColumn('bil_juruaudit', function ($row) {

                return '<span class="badge bg-primary">'
                    . $row->members->count() .
                    ' Juruaudit</span>';
            })
            ->addColumn('status', function ($row) {
                if ($row->status == 'BELUM BERMULA') {
                    return '<span class="badge bg-secondary">' . $row->status . '</span>';
                } elseif ($row->status == 'DALAM PROSES') {
                    return '<span class="badge bg-warning">' . $row->status . '</span>';
                } elseif ($row->status == 'SELESAI') {
                    return '<span class="badge bg-success">' . $row->status . '</span>';
                } else {
                    return '<span class="badge bg-danger">' . $row->status . '</span>';
                }
            })
            ->addColumn('created_by', function ($row) {
                $name = optional($row->user)->name;
                $date = date('d/m/Y H:i:a', strtotime($row->created_at));
                return $name . '<br/>&emsp;' . $date;
            })
            ->addColumn('updated_by', function ($row) {
                if ($row->updated_by) {
                    $name = optional($row->useru)->name;
                    $date = date('d/m/Y H:i:a', strtotime($row->updated_at));
                    return $name . '<br/>&emsp;' . $date;
                } else {
                    return '-';
                }
            })
            ->addColumn('tindakan', function ($row) {
                $btn = '';
                if ($row->status == 'BELUM BERMULA') {
                    $btn .= ' <a href="' . route('auditgroup.edit', encode($row->id)) . '" class="btn btn-warning btn-sm" title="Edit"><i class="material-icons-outlined">edit</i></a>';
                    $btn .= ' <a href="' . route('auditgroup.destroy', encode($row->id)) . '" class="btn btn-danger btn-sm" title="Delete"><i class="material-icons-outlined">delete</i></a>';
                } else {
                    $btn .= ' <a href="' . route('auditgroup.edit', encode($row->id)) . '" class="btn btn-secondary btn-sm" title="View"><i class="material-icons-outlined">open_in_new</i></a>';
                }
                return $btn;
            })
            ->rawColumns(['audit_template_id', 'bil_juruaudit', 'status', 'created_by', 'updated_by', 'tindakan'])
            ->make(true);
    }

    /**
     * Display the specified resource.
     */
    public function show(AuditGroups $auditGroups)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $users = User::role('Auditor')
            ->where('status', 'AKTIF')
            ->whereNotIn('id', function ($q) use ($id) {
                $q->select('user_id')
                    ->from('audit_groups_members')
                    ->where('audit_group_id', decode($id));
            })
            ->orderBy('name')
            ->get();
        $hasLeader = AuditGroupsMembers::where('audit_group_id', decode($id))
            ->where('role', 'Leader')
            ->exists();
        $auditGroup = AuditGroups::find(decode($id));
        $auditTemplates = AuditTemplate::orderBy('name', 'ASC')->where('status', 'PUBLISHED')->get();
        return view('auditgroup.edit', compact('auditGroup', 'auditTemplates', 'users', 'hasLeader'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'template' => 'required|string',
            'jabatan' => 'required|string',
        ], [
            'name.required' => 'Nama group wajib diisi',
            'template.required' => 'Template wajib dipilih',
            'jabatan.required' => 'Jabatan wajib diisi',
        ]);
        $auditGroup = AuditGroups::find(decode($id));
        $auditGroup->name = strtoupper($request->name);
        $auditGroup->audit_template_id = $request->template;
        $auditGroup->jabatan = $request->jabatan;
        $auditGroup->tarikh = $request->tarikh;
        $auditGroup->updated_by = \Auth::user()->id;
        $auditGroup->save();
        auditTrail('Update', 'Tetapan Audit', 'Audit Group', $auditGroup->id, $auditGroup->name, \Auth::user()->id);
        return redirect()->route('auditgroup.edit', encode($auditGroup->id))->with('success', 'Group berjaya diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $auditGroup = AuditGroups::find(decode($id));
        $auditGroup->delete();
        auditTrail('Delete', 'Tetapan Audit', 'Audit Group', $auditGroup->id, $auditGroup->name, \Auth::user()->id);
        return redirect()->back()->with('success', 'Group berjaya dihapus');
    }

    public function answers($id)
    {
        $auditGroup = AuditGroups::with([
            'auditTemplate.items.checklists',
            'members.pengguna',
            'answers.auditor',
            'answers.checklists',
            'answers.files',
        ])->findOrFail(decode($id));

        if ($auditGroup->status != 'SELESAI') {
            return redirect()
                ->route('auditgroup')
                ->with('error', 'Jawapan audit hanya boleh dilihat setelah audit selesai.');
        }

        return view('auditgroup.answer', compact('auditGroup'));
    }
}
