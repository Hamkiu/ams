<?php

namespace App\Http\Controllers;

use App\Models\AuditTemplateItems;
use App\Models\AuditTemplate;
use App\Models\AuditItemChecklist;
use Yajra\DataTables\Facades\DataTables;


use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            'sort' => ['required','integer',
                Rule::unique('audit_template_items')
                    ->where(function ($query) use ($id) {
                        return $query->where('audit_template_id', decode($id));
                }),
            ],
            'perkara' => 'required|string',
            'no_klausa' => 'required|string',
            'klausa' => 'required|string',
        ], [
            'sort.required' => 'Sort wajib diisi',
            'sort.integer'  => 'Sort mestilah nombor',
            'sort.unique'   => 'Nombor sort telah digunakan bagi template ini.',
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
        $data = AuditTemplateItems::withCount('checklists')
        ->where('audit_template_id', decode($id))
        ->orderBy('sort')
        ->get();        
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('is_active', function ($row) {

                $btn = '<div class="d-flex justify-content-center align-items-center">';

                if ($row->is_active) {

                    $btn .= '<a href="' . route('audittemplate.items.actionStatus', [encode($row->id), 0]) . '"
                                class="btn btn-success btn-circle raised rounded-circle wh-48"
                                title="Aktif">
                                <i class="material-icons-outlined">toggle_on</i>
                            </a>';

                } else {

                    $btn .= '<a href="' . route('audittemplate.items.actionStatus', [encode($row->id), 1]) . '"
                                class="btn btn-danger btn-circle raised rounded-circle wh-48"
                                title="Tidak Aktif">
                                <i class="material-icons-outlined">toggle_off</i>
                            </a>';

                }

                $btn .= '</div>';

                return $btn;
            })
            ->addColumn('senarai_penemuan_audit', function ($row) {

                return '<span class="badge bg-primary">'
                        .$row->checklists_count.
                        ' Senarai</span>';

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
                $btn .= ' <button type="button" class="btn btn-primary btn-sm editItem" data-id="'.encode($row->id).'" title="Edit"><i class="material-icons-outlined">edit</i></button>';
                $btn .= ' <a href="'.route('audittemplate.checklist', encode($row->id)).'" class="btn btn-warning btn-sm" title="checklist"><i class="material-icons-outlined">settings</i></a>';
                $btn .= ' <a href="'.route('audittemplate.items.destroy', encode($row->id)).'" class="btn btn-danger btn-sm" title="Delete"><i class="material-icons-outlined">delete</i></a>';
                return $btn;
            })
            ->rawColumns(['is_active', 'senarai_penemuan_audit', 'created_by', 'updated_by', 'tindakan'])
            ->make(true);
    }

    public function actionStatus($id, $status)
    {
        $auditTemplateItems = AuditTemplateItems::find(decode($id));
        if ($status == 0) {
            $auditTemplateItems->is_active = 0;
        } else {
            $auditTemplateItems->is_active = 1;
        }
        $auditTemplateItems->save();
        auditTrail('Update', 'Tetapan Audit', 'Audit Template Item', $auditTemplateItems->id, $auditTemplateItems->perkara, \Auth::user()->id);
        return redirect()->back();
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
        $auditTemplateItems = AuditTemplateItems::find(decode($id));
        $validated = $request->validate([
            'sort' => [
                'required',
                'integer',
                Rule::unique('audit_template_items')
                    ->where(function ($query) use ($auditTemplateItems) {
                        return $query->where('audit_template_id', $auditTemplateItems->audit_template_id);
                    })
                    ->ignore($auditTemplateItems->id),
            ],
            'perkara' => 'required|string',
            'no_klausa' => 'required|string',
            'klausa' => 'required|string',
        ], [
            'sort.required' => 'Sort wajib diisi',
            'sort.integer'  => 'Sort mestilah nombor',
            'sort.unique'   => 'Nombor sort telah digunakan bagi template ini.',
            'perkara.required' => 'Perkara wajib diisi',
            'no_klausa.required' => 'No Klausa wajib diisi',
            'klausa.required' => 'Klausa wajib diisi',
        ]);
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
    public function destroy($id)
    {
        $auditTemplateItems = AuditTemplateItems::find(decode($id));
        $auditItemChecklists = AuditItemChecklist::where('items_id', decode($id))->get();
        foreach ($auditItemChecklists as $item) {
            $item->delete();
        }
        $auditTemplateItems->delete();
        auditTrail('Delete', 'Tetapan Audit', 'Audit Template Item', $auditTemplateItems->id, $auditTemplateItems->perkara, \Auth::user()->id);
        return redirect()->route('audittemplate.items', encode($auditTemplateItems->audit_template_id))->with('success', 'Item dan senarai penemuan audit berjaya dihapus');
    }
}
