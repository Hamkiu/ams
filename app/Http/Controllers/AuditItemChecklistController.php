<?php

namespace App\Http\Controllers;

use App\Models\AuditItemChecklist;
use App\Models\AuditTemplateItems;
use App\Models\AuditTemplate;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;

class AuditItemChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $auditTemplateItems = AuditTemplateItems::find(decode($id));
        $auditTemplate = AuditTemplate::find($auditTemplateItems->audit_template_id);
        return view('checklist.index', compact('auditTemplateItems', 'auditTemplate'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        // dd(request()->all());
        $validated = $request->validate([
            'name' => 'required|string',
        ], [
            'name.required' => 'Nama penemuan audit wajib diisi',
        ]);
        $auditTemplateItems = AuditTemplateItems::find(decode($id));
        $auditItemChecklist = AuditItemChecklist::create([
            'items_id' => $auditTemplateItems->id,
            'name' => $request->name,
            'created_by' => \Auth::user()->id,
        ]);
        auditTrail('Create', 'Tetapan Audit', 'Item Checklist', $auditItemChecklist->id, $auditItemChecklist->name, \Auth::user()->id);
        return redirect()->route('audittemplate.checklist', encode($auditTemplateItems->id))->with('success', 'Senarai Penemuan Audit berjaya disimpan');
    }

    public function list(Request $request, $id)
    {
        $auditItemChecklists = AuditItemChecklist::where('items_id', decode($id))->get();
        return DataTables::of($auditItemChecklists)
            ->addIndexColumn()
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
                $btn .= ' <button type="button" class="btn btn-primary btn-sm editChecklist" data-id="'.encode($row->id).'" title="Edit"><i class="material-icons-outlined">edit</i></button>';
                $btn .= ' <a href="'.route('audittemplate.checklist.destroy', encode($row->id)).'" class="btn btn-danger btn-sm" title="Delete"><i class="material-icons-outlined">delete</i></a>';
                return $btn;
            })
            ->rawColumns(['created_by', 'updated_by', 'tindakan'])
            ->make(true);
    }

    /**
     * Display the specified resource.
     */
    public function show(AuditItemChecklist $auditItemChecklist)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $auditItemChecklist = AuditItemChecklist::find(decode($id));
        return view('checklist.edit', compact('auditItemChecklist'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
        ], [
            'name.required' => 'Nama penemuan audit wajib diisi',
        ]);
        $auditItemChecklist = AuditItemChecklist::find(decode($id));
        $auditItemChecklist->update([
            'name' => $request->name,
            'updated_by' => \Auth::user()->id,
        ]);
        auditTrail('Update', 'Tetapan Audit', 'Item Checklist', $auditItemChecklist->id, $auditItemChecklist->name, \Auth::user()->id);
        return redirect()->route('audittemplate.checklist', encode($auditItemChecklist->items_id))->with('success', 'Senarai Penemuan Audit berjaya dikemaskini');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $auditItemChecklist = AuditItemChecklist::find(decode($id));
        $auditItemChecklist->delete();
        auditTrail('Delete', 'Tetapan Audit', 'Item Checklist', $auditItemChecklist->id, $auditItemChecklist->name, \Auth::user()->id);
        return redirect()->route('audittemplate.checklist', encode($auditItemChecklist->items_id))->with('success', 'Senarai Penemuan Audit berjaya dihapus');
    }
}
