<?php

namespace App\Http\Controllers;

use App\Models\AuditGroupsMembers;
use App\Models\AuditGroups;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AuditGroupsMembersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:Leader,Member',
        ], [
            'user_id.required' => 'Juruaudit wajib dipilih',
            'user_id.exists' => 'Juruaudit tidak ditemukan',
            'role.required' => 'Peranan wajib dipilih',
            'role.in' => 'Peranan tidak valid',
        ]);
        $sort = AuditGroupsMembers::where('audit_group_id', decode($id))
            ->max('sort');
        $sort = $sort ? $sort + 1 : 1;
        $auditGroup = AuditGroups::find(decode($id));
        $auditGroup->members()->create([
            'audit_group_id' => decode($id),
            'user_id' => $request->user_id,
            'jabatan' => $request->jabatan,
            'sort' => $sort,
            'role' => $request->role,
            'remarks' => $request->remarks,
            'created_by' => \Auth::user()->id,
        ]);
        auditTrail('Create', 'Tetapan Audit', 'Audit Group Member', $auditGroup->id, $auditGroup->name, \Auth::user()->id);
        return response()->json([
            'success' => true,
            'message' => 'Juruaudit berjaya ditambahkan',
        ]);    
    }

    public function list(Request $request, $id)
    {
        $data = AuditGroupsMembers::where('audit_group_id', decode($id))
            ->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('user_id', function ($row) {
                return $row->pengguna->name;
            })
            ->addColumn('jabatan', function ($row) {
                return $row->jabatan;
            })
            ->addColumn('role', function ($row) {
                return $row->role;
            })
            ->addColumn('created_by', function ($row) {
                $name = optional($row->user)->name;
                $date = date('d/m/Y H:i:a', strtotime($row->created_at));
                return $name.'<br/>&emsp;'.$date;
            })
            ->addColumn('tindakan', function ($row) {
                $btn = '';
                $btn .= ' <a href="'.route('auditgroupmember.destroy', encode($row->id)).'" class="btn btn-danger btn-sm" title="Delete"><i class="material-icons-outlined">delete</i></a>';
                return $btn;
            })
            ->rawColumns(['user_id', 'jabatan', 'role', 'created_by', 'updated_by', 'tindakan'])
            ->make(true);
    }

    /**
     * Display the specified resource.
     */
    public function show(AuditGroupsMembers $auditGroupsMembers)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AuditGroupsMembers $auditGroupsMembers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AuditGroupsMembers $auditGroupsMembers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $auditGroupMember = AuditGroupsMembers::find(decode($id));
        $auditGroupMember->delete();
        auditTrail('Delete', 'Tetapan Audit', 'Audit Group Member', $auditGroupMember->id, $auditGroupMember->name, \Auth::user()->id);
        return redirect()->route('auditgroup.edit', encode($auditGroupMember->audit_group_id))->with('success', 'Juruaudit berjaya dihapus');
    }
}
