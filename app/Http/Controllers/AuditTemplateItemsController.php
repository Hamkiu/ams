<?php

namespace App\Http\Controllers;

use App\Models\AuditTemplateItems;
use App\Models\AuditTemplate;
use Yajra\DataTables\Facades\DataTables;

use Illuminate\Http\Request;

class AuditTemplateItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $auditTemplate = AuditTemplate::find(decode($id));
        return view('item.index', compact('auditTemplate'));
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
        // dd($request->all());
        $validated = $request->validate([
            'sort' => 'required|integer',
            'perkara' => 'required|string',
            'no_klausa' => 'required|string',
            'klausa' => 'required|string',
        ], [
            'sort.required' => 'Sort wajib diisi',
            'perkara.required' => 'Perkara wajib diisi',
            'no_klausa.required' => 'No Klausa wajib diisi',
            'klausa.required' => 'Klausa wajib diisi',
        ]);
        $auditTemplate = AuditTemplate::find(decode($id));
        $auditTemplateItems = AuditTemplateItems::create([
            'audit_template_id' => $auditTemplate->id,
            'sort' => $request->sort,
            'perkara' => $request->perkara,
            'no_klausa' => $request->no_klausa,
            'klausa' => $request->klausa,
            'keterangan' => $request->keterangan,
            'created_by' => \Auth::user()->id,
        ]);
        auditTrail('Create', 'Tetapan Audit', 'Audit Template Item', $auditTemplateItems->id, $auditTemplateItems->perkara, \Auth::user()->id);
        return redirect()->route('audittemplate.items', encode($auditTemplate->id))->with('success', 'Item berjaya disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function list(Request $request, $id)
    {
        $data = AuditTemplateItems::where('audit_template_id', decode($id))->orderBy('sort')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('is_active', function ($row) {
                $badge = $row->is_active ? 'success' : 'danger';
                $status = $row->is_active ? 'Aktif' : 'Tidak Aktif';
    
                return '<span class="badge bg-' . $badge . '">' .
                            $status .
                       '</span>';            })
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
                $btn .= ' <button type="button" class="btn btn-primary btn-sm editItem" data-id="'.encode($row->id).'" title="Edit"><i class="material-icons-outlined">edit</i></button>';
                $btn .= ' <a href="" class="btn btn-warning btn-sm" title="checklist"><i class="material-icons-outlined">settings</i></a>';
                $btn .= ' <a href="" class="btn btn-danger btn-sm" title="Delete"><i class="material-icons-outlined">delete</i></a>';
                return $btn;
            })
            ->rawColumns(['is_active', 'created_by', 'updated_by', 'tindakan'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $auditTemplateItems = AuditTemplateItems::find(decode($id));
        return view('item.edit', compact('auditTemplateItems'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'sort' => 'required|integer',
            'perkara' => 'required|string',
            'no_klausa' => 'required|string',
            'klausa' => 'required|string',
        ], [
            'sort.required' => 'Sort wajib diisi',
            'perkara.required' => 'Perkara wajib diisi',
            'no_klausa.required' => 'No Klausa wajib diisi',
            'klausa.required' => 'Klausa wajib diisi',
        ]);
        $auditTemplateItems = AuditTemplateItems::find(decode($id));
        $auditTemplateItems->update([
            'sort' => $request->sort,
            'perkara' => $request->perkara,
            'no_klausa' => $request->no_klausa,
            'klausa' => $request->klausa,
            'keterangan' => $request->keterangan,
            'updated_by' => \Auth::user()->id,
        ]);
        auditTrail('Update', 'Tetapan Audit', 'Audit Template Item', $auditTemplateItems->id, $auditTemplateItems->perkara, \Auth::user()->id);
        return redirect()->route('audittemplate.items', encode($auditTemplateItems->audit_template_id))->with('success', 'Item berjaya dikemaskini');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AuditTemplateItems $auditTemplateItems)
    {
        //
    }
}
