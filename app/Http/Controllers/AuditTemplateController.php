<?php

namespace App\Http\Controllers;

use App\Models\AuditTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

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
    public function create()
    {
        //
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

        $auditTemplate = AuditTemplate::create([
            'name' => $request->name,
            'no_rujukan' => $request->no_rujukan,
            'no_pindaan' => $request->no_pindaan,
            'version' => $request->version,
            'tarikh_berkuatkuasa' => $request->tarikh_berkuatkuasa,
            'status' => 'DRAFT',
            'created_by' => \Auth::user()->id,
        ]);

        auditTrail('Create', 'Audit Template', 'Audit Template', $auditTemplate->id, $auditTemplate->name, \Auth::user()->id);
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
                return '<span class="badge bg-'.($row->status == 'DRAFT' ? 'warning' : 'success').'">'.$row->status.'</span>';
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
            ->addColumn('tarikh_berkuatkuasa', function ($row) {
                return $row->tarikh_berkuatkuasa->format('d-m-Y');
            })
            ->addColumn('tindakan', function ($row) {
                $btn = '';
                $btn .= '<a href="'.route('audittemplate.edit', encode($row->id)).'" class="btn btn-primary btn-sm" title="Edit"><i class="material-icons-outlined">edit</i></a>';
                $btn .= ' <a href="" class="btn btn-warning btn-sm" title="Items"><i class="material-icons-outlined">settings</i></a>';
                $btn .= ' <a href="'.route('audittemplate.destroy', encode($row->id)).'" class="btn btn-danger btn-sm" title="Delete"><i class="material-icons-outlined">delete</i></a>';
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
    public function update(Request $request, AuditTemplate $auditTemplate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $auditTemplate = AuditTemplate::find(decode($id));
        $auditTemplate->delete();
        auditTrail('Delete', 'Audit Template', 'Audit Template', $auditTemplate->id, $auditTemplate->name, \Auth::user()->id);
        return redirect()->route('audittemplate')->with('success', 'Template audit berjaya dipadam');
    }
}
