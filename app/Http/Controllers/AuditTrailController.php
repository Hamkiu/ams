<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class AuditTrailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('audittrail.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function list(Request $request)
    {
        $log = AuditTrail::orderBy('created_at', 'desc');
        if (!empty($request->module)) {
            $log->where('audit_trail_module','like','%'.$request->module.'%');
        }
        if (!empty($request->component)) {
            $log->where('audit_trail_component','like','%'.$request->component.'%');
        }
        if (!empty($request->action)) {
            $log->where('audit_trail_action','like','%'.$request->action.'%');
        }
        if (!empty($request->action_by)) {
            $log->where('users_id',$request->action_by);
        }
        if (!empty($request->action_at)) {
            $log->where('created_at','like','%'.$request->action_at.'%');
        }
        if (!empty($request->desc)) {
            $log->where('audit_trail_desc','like','%'.$request->desc.'%');
        }
        $log = $log->get();

        $datatable = Datatables::of($log)
            ->addIndexColumn()
            ->addColumn('module', function ($row) {
                return $row->audit_trail_module;
            })
            ->addColumn('component', function ($row) {
                return $row->audit_trail_component;
            })
            ->addColumn('action', function ($row) {
                return $row->audit_trail_action;
            })
            ->addColumn('actionby', function ($row) {
                return $row->user->name;
            })
            ->addColumn('actionat', function ($row) {
                return date('d/m/Y H:i:a', strtotime($row->created_at));
            })
            ->addColumn('description', function ($row) {
                return $row->audit_trail_desc;
            })
            ->rawColumns(['module', 'component', 'action', 'description'])
            ->make(true);

        return $datatable;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AuditTrail $auditTrail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AuditTrail $auditTrail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AuditTrail $auditTrail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AuditTrail $auditTrail)
    {
        //
    }
}
