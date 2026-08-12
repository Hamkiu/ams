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
        $validated = $request->validate(
            [
                'user_id' => 'required|exists:users,id',
                'role'    => 'required|in:Leader,Member',
            ],
            [
                'user_id.required' => 'Juruaudit wajib dipilih',
                'user_id.exists'   => 'Juruaudit tidak ditemukan',
                'role.required'    => 'Peranan wajib dipilih',
                'role.in'          => 'Peranan tidak sah',
            ]
        );

        $auditGroupId = decode($id);

        // Semak juruaudit telah wujud dalam kumpulan
        $existMember = AuditGroupsMembers::where('audit_group_id', $auditGroupId)
            ->where('user_id', $request->user_id)
            ->exists();

        if ($existMember) {
            return response()->json([
                'success' => false,
                'message' => 'Juruaudit ini telah berada di dalam kumpulan.',
            ], 422);
        }

        // Semak Ketua Juruaudit hanya seorang
        if ($request->role == 'Leader') {

            $existLeader = AuditGroupsMembers::where('audit_group_id', $auditGroupId)
                ->where('role', 'Leader')
                ->exists();

            if ($existLeader) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ketua Juruaudit telah ditetapkan bagi kumpulan ini.',
                ], 422);
            }
        }

        // Susunan ahli
        $sort = AuditGroupsMembers::where('audit_group_id', $auditGroupId)
            ->max('sort');

        $sort = $sort ? $sort + 1 : 1;

        $auditGroup = AuditGroups::findOrFail($auditGroupId);

        $auditGroup->members()->create([
            'audit_group_id' => $auditGroupId,
            'user_id'        => $request->user_id,
            'jabatan'        => $request->jabatan_member,
            'sort'           => $sort,
            'role'           => $request->role,
            'remarks'        => $request->remarks,
            'created_by'     => auth()->id(),
        ]);

        auditTrail(
            'Create',
            'Tetapan Audit',
            'Audit Group Member',
            $auditGroup->id,
            $auditGroup->name,
            auth()->id()
        );

        return response()->json([
            'success' => true,
            'message' => 'Juruaudit berjaya ditambahkan.',
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
                return $name . '<br/>&emsp;' . $date;
            })
            ->addColumn('tindakan', function ($row) {
                $btn = '';
                if (in_array($row->auditGroup->status, ['DALAM PROSES', 'SELESAI'])) {
                    return '-';
                }
                $btn .= ' <a href="' . route('auditgroupmember.destroy', encode($row->id)) . '" class="btn btn-danger btn-sm" title="Delete"><i class="material-icons-outlined">delete</i></a>';
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
