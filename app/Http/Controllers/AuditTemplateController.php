<?php

namespace App\Http\Controllers;

use App\Models\AuditTemplate;
use App\Models\AuditTemplateItems;
use App\Models\AuditItemChecklist;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class AuditTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('template.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show($id)
    {
        $auditTemplate = AuditTemplate::find(decode($id));
        return view('template.show', compact('auditTemplate'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required',
            'no_rujukan' => 'required',
        ], [
            'name.required' => 'Nama template wajib diisi',
            'no_rujukan.required' => 'No rujukan wajib diisi',
        ]);
        $templateId = generateId('AT', 'audit_templates', 'id');
        $auditTemplate = AuditTemplate::create([
            'id' => $templateId,
            'name' => strtoupper($request->name),
            'no_rujukan' => strtoupper($request->no_rujukan),
            'klausa' => strtoupper($request->klausa),
            'no_pindaan' => $request->no_pindaan,
            'version' => $request->version,
            'tarikh_berkuatkuasa' => $request->tarikh_berkuatkuasa,
            'description' => $request->description,
            'status' => 'DRAFT',
            'created_by' => \Auth::user()->id,
        ]);

        auditTrail('Create', 'Tetapan Audit', 'Audit Template', $auditTemplate->id, $auditTemplate->name, \Auth::user()->id);
        return redirect()->route('audittemplate')->with('success', 'Template audit berjaya disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function list(Request $request)
    {
        $data = AuditTemplate::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('status', function ($row) {

                $badge = match ($row->status) {
                    'DRAFT'   => 'warning',
                    'PUBLISHED'   => 'success',
                    'ARCHIVE' => 'dark',
                    default   => 'secondary',
                };

                return '<span class="badge bg-' . $badge . '">' .
                    $row->status .
                    '</span>';
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
            ->addColumn('tarikh_berkuatkuasa', function ($row) {
                return $row->tarikh_berkuatkuasa->format('d-m-Y');
            })
            ->addColumn('tindakan', function ($row) {
                $btn = '';
                if ($row->status == 'DRAFT') {
                    $btn .= ' <button type="button" class="btn btn-primary btn-sm editTemplate" data-id="' . encode($row->id) . '" title="Edit"><i class="material-icons-outlined">edit</i></button>';
                    $btn .= ' <a href="' . route('audittemplate.items', encode($row->id)) . '" class="btn btn-warning btn-sm" title="Items"><i class="material-icons-outlined">settings</i></a>';
                    $btn .= ' <a href="' . route('audittemplate.publish', encode($row->id)) . '" class="btn btn-success btn-sm" title="Publish"><i class="material-icons-outlined">publish</i></a>';
                    $btn .= ' <a href="' . route('audittemplate.destroy', encode($row->id)) . '" class="btn btn-danger btn-sm" title="Delete"><i class="material-icons-outlined">delete</i></a>';
                    return $btn;
                } else if ($row->status == 'PUBLISHED') {
                    $btn .= ' <a href="' . route('audittemplate.preview', encode($row->id)) . '" class="btn btn-info btn-sm" title="Preview"><i class="material-icons-outlined">preview</i></a>';
                    $btn .= ' <a href="' . route('audittemplate.duplicate', encode($row->id)) . '" class="btn btn-secondary btn-sm" title="Duplicate"><i class="material-icons-outlined">copy</i></a>';
                    $btn .= ' <a href="' . route('audittemplate.archive', encode($row->id)) . '" class="btn btn-dark btn-sm" title="Archive"><i class="material-icons-outlined">archive</i></a>';
                } else if ($row->status == 'ARCHIVED') {
                    $btn .= ' <a href="' . route('audittemplate.items', encode($row->id)) . '" class="btn btn-warning btn-sm" title="Items"><i class="material-icons-outlined">settings</i></a>';
                    $btn .= ' <a href="' . route('audittemplate.duplicate', encode($row->id)) . '" class="btn btn-warning btn-sm" title="Duplicate"><i class="material-icons-outlined">copy</i></a>';
                    $btn .= ' <a href="' . route('audittemplate.publish', encode($row->id)) . '" class="btn btn-success btn-sm" title="Publish"><i class="material-icons-outlined">publish</i></a>';
                }
                return $btn;
            })
            ->rawColumns(['status', 'created_by', 'updated_by', 'tarikh_berkuatkuasa', 'tindakan'])
            ->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AuditTemplate $auditTemplate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'no_rujukan' => 'required',
        ], [
            'name.required' => 'Nama template wajib diisi',
            'no_rujukan.required' => 'No rujukan wajib diisi',
        ]);

        $auditTemplate = AuditTemplate::find(decode($id));
        $auditTemplate->update([
            'name' => strtoupper($request->name),
            'no_rujukan' => strtoupper($request->no_rujukan),
            'klausa' => strtoupper($request->klausa),
            'no_pindaan' => $request->no_pindaan,
            'version' => $request->version,
            'tarikh_berkuatkuasa' => $request->tarikh_berkuatkuasa,
            'description' => $request->description,
            'updated_by' => \Auth::user()->id,
        ]);
        auditTrail('Update', 'Tetapan Audit', 'Audit Template', $auditTemplate->id, $auditTemplate->name, \Auth::user()->id);
        return redirect()->route('audittemplate')->with('success', 'Template ' . $auditTemplate->id . ' berjaya dikemaskini');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $auditTemplate = AuditTemplate::find(decode($id));
        $auditTemplateItems = AuditTemplateItems::where('audit_template_id', decode($id))->get();
        foreach ($auditTemplateItems as $item) {
            $auditItemChecklists = AuditItemChecklist::where('items_id', $item->id)->get();
            foreach ($auditItemChecklists as $checklist) {
                $checklist->delete();
            }
            $item->delete();
        }
        $auditTemplate->delete();
        auditTrail('Delete', 'Tetapan Audit', 'Audit Template', $auditTemplate->id, $auditTemplate->name, \Auth::user()->id);
        return redirect()->route('audittemplate')->with('success', 'Template ' . $auditTemplate->id . ' berjaya dipadam');
    }

    public function publish($id)
    {
        $auditTemplate = AuditTemplate::find(decode($id));
        $auditTemplate->update([
            'status' => 'PUBLISHED',
            'updated_by' => \Auth::user()->id,
        ]);
        auditTrail('Publish', 'Tetapan Audit', 'Audit Template', $auditTemplate->id, $auditTemplate->name, \Auth::user()->id);
        return redirect()->route('audittemplate')->with('success', 'Template ' . $auditTemplate->id . ' berjaya diaktifkan');
    }

    public function archive($id)
    {
        $auditTemplate = AuditTemplate::find(decode($id));
        $auditTemplate->update([
            'status' => 'ARCHIVED',
            'updated_by' => \Auth::user()->id,
        ]);
        auditTrail('Archive', 'Tetapan Audit', 'Audit Template', $auditTemplate->id, $auditTemplate->name, \Auth::user()->id);
        return redirect()->route('audittemplate')->with('success', 'Template ' . $auditTemplate->id . ' berjaya diarsipkan');
    }

    public function preview($id)
    {
        $auditTemplate = AuditTemplate::with([
            'items.checklists'
        ])->findOrFail(decode($id));

        return view('template.preview', compact('auditTemplate'));
    }

    public function duplicate($id)
    {
        DB::beginTransaction();

        try {

            /*
        |--------------------------------------------------------------------------
        | AMBIL TEMPLATE ASAL
        |--------------------------------------------------------------------------
        */

            $auditTemplate = AuditTemplate::with('items.checklists')
                ->findOrFail(decode($id));


            /*
        |--------------------------------------------------------------------------
        | DUPLICATE TEMPLATE
        |--------------------------------------------------------------------------
        */

            $templateId = generateId('AT', 'audit_templates', 'id');

            $newTemplate = AuditTemplate::create([
                'id' => $templateId,
                'name' => $auditTemplate->name . ' - SALINAN',
                'no_rujukan' => $auditTemplate->no_rujukan,
                'klausa' => $auditTemplate->klausa,
                'no_pindaan' => $auditTemplate->no_pindaan,
                'version' => $auditTemplate->version,
                'tarikh_berkuatkuasa' => $auditTemplate->tarikh_berkuatkuasa,
                'description' => $auditTemplate->description,
                'status' => 'DRAFT',
                'created_by' => \Auth::user()->id,
            ]);


            /*
        |--------------------------------------------------------------------------
        | DUPLICATE ITEM
        |--------------------------------------------------------------------------
        */

            foreach ($auditTemplate->items as $item) {

                $newItem = AuditTemplateItems::create([
                    'audit_template_id' => $newTemplate->id,
                    'sort' => $item->sort,
                    'perkara' => $item->perkara,
                    'no_klausa' => $item->no_klausa,
                    'klausa' => $item->klausa,
                    'keterangan' => $item->keterangan,
                    'is_active' => $item->is_active,
                    'created_by' => \Auth::user()->id,
                ]);


                /*
            |--------------------------------------------------------------------------
            | DUPLICATE CHECKLIST ITEM
            |--------------------------------------------------------------------------
            */

                foreach ($item->checklists as $checklist) {

                    AuditItemChecklist::create([
                        'items_id' => $newItem->id,
                        'name' => $checklist->name,
                        'created_by' => \Auth::user()->id,
                    ]);
                }
            }


            /*
        |--------------------------------------------------------------------------
        | AUDIT TRAIL
        |--------------------------------------------------------------------------
        */

            auditTrail(
                'Create',
                'Tetapan Audit',
                'Duplicate Audit Template',
                $newTemplate->id,
                $newTemplate->name,
                \Auth::user()->id
            );


            DB::commit();

            return redirect()
                ->route('audittemplate')
                ->with('success', 'Template audit berjaya diduplikasi');
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->route('audittemplate')
                ->with('error', 'Template audit gagal diduplikasi: ' . $e->getMessage());
        }
    }
}
