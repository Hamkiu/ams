<?php

namespace App\Http\Controllers;

use App\Models\AuditGroups;
use App\Models\AuditTemplate;
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
        $auditTemplates = AuditTemplate::orderBy('name','ASC')->where('status', 'PUBLISHED')->get();
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
            'name' => $request->name,
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
            ->addColumn('created_by', function ($row) {
                $name = optional($row->user)->name;
                $date = date('d/m/Y H:i:a', strtotime($row->created_at));
                return $name.'<br/>&emsp;'.$date;
            })
            ->addColumn('updated_by', function ($row) {
                if($row->updated_by){
                    $name = optional($row->useru)->name;
                    $date = date('d/m/Y H:i:a', strtotime($row->updated_at));
                    return $name.'<br/>&emsp;'.$date;
                }else{
                    return '-';
                }
            })
            ->addColumn('tindakan', function ($row) {
                $btn = '';
                $btn .= ' <a href="'.route('auditgroup.edit', encode($row->id)).'" class="btn btn-warning btn-sm" title="Edit"><i class="material-icons-outlined">edit</i></a>';
                $btn .= ' <a href="'.route('auditgroup.destroy', encode($row->id)).'" class="btn btn-danger btn-sm" title="Delete"><i class="material-icons-outlined">delete</i></a>';
                return $btn;
            })
            ->rawColumns(['audit_template_id', 'created_by', 'updated_by', 'tindakan'])
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
        $auditGroup = AuditGroups::find(decode($id));
        $auditTemplates = AuditTemplate::orderBy('name','ASC')->where('status', 'PUBLISHED')->get();
        return view('auditgroup.edit', compact('auditGroup', 'auditTemplates'));
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
        $auditGroup->name = $request->name;
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
}
