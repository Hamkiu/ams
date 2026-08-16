<?php

namespace App\Http\Controllers;

use App\Models\AuditAdminReviews;
use App\Models\AuditGroups;
use App\Models\AuditGroupsMembers;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AuditAdminReviewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auditadminreview.index');
    }

    public function list(Request $request)
    {
        $data = AuditGroups::whereIn('status', [
            'MENUNGGU ULASAN',
            'SELESAI'
        ])->get();
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
                if ($row->status == 'MENUNGGU ULASAN') {
                    return '<span class="badge bg-danger">' . $row->status . '</span>';
                } else {
                    return '<span class="badge bg-success">' . $row->status . '</span>';
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
                if ($row->status == 'MENUNGGU ULASAN') {
                    $btn .= ' <a href="' . route('auditadminreview.create', encode($row->id)) . '" class="btn btn-secondary btn-sm" title="View"><i class="material-icons-outlined">open_in_new</i></a>';
                } else {
                    $btn .= ' <a href="' . route('auditadminreview.edit', encode($row->id)) . '" class="btn btn-secondary btn-sm" title="View"><i class="material-icons-outlined">open_in_new</i></a>';
                    $btn .= ' <a href="' . route('auditadminreview.answers', encode($row->id)) . '" class="btn btn-success btn-sm" title="Lihat Jawapan"><i class="material-icons-outlined">fact_check</i></a>';
                }
                return $btn;
            })
            ->rawColumns(['audit_template_id', 'bil_juruaudit', 'status', 'created_by', 'updated_by', 'tindakan'])
            ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $groupId = decode($id);

        /*
        |--------------------------------------------------------------------------
        | Ambil Audit Group
        |--------------------------------------------------------------------------
        */
        $auditGroup = AuditGroups::with([
            'auditTemplate.items.checklists',
            'members.pengguna',
            'answers.auditor',
            'answers.checklists',
            'answers.files',
            'conclusion',
        ])->findOrFail($groupId);

        /*
        |--------------------------------------------------------------------------
        | Admin hanya boleh akses selepas Ketua hantar rumusan
        |--------------------------------------------------------------------------
        */
        if (!in_array($auditGroup->status, [
            'MENUNGGU ULASAN',
            'SELESAI',
        ])) {

            return redirect()
                ->route('auditadminreview')
                ->with(
                    'error',
                    'Audit ini belum tersedia untuk ulasan Admin.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Readonly jika audit telah selesai
        |--------------------------------------------------------------------------
        */
        $readonly = $auditGroup->status === 'SELESAI';

        return view('auditadminreview.create', compact(
            'auditGroup',
            'readonly'
        ));
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
    public function show(AuditAdminReviews $auditAdminReviews)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AuditAdminReviews $auditAdminReviews)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AuditAdminReviews $auditAdminReviews)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AuditAdminReviews $auditAdminReviews)
    {
        //
    }
}
