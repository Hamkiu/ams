<?php

namespace App\Http\Controllers;

use App\Models\AuditAdminReviews;
use App\Models\AuditGroups;
use App\Models\AuditGroupsMembers;
use App\Models\AuditFiles;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

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
            ->addColumn('klausa', function ($row) {
                return $row->auditTemplate->klausa;
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
                    $btn .= ' <a href="' . route('auditadminreview.create', encode($row->id)) . '" class="btn btn-warning btn-sm" title="create review"><i class="material-icons-outlined">comment</i></a>';
                } else {
                    $btn .= ' <a href="' . route('auditadminreview.create', encode($row->id)) . '" class="btn btn-success btn-sm" title="Lihat Jawapan"><i class="material-icons-outlined">fact_check</i></a>';
                    $btn .= ' <a href="' . route('auditadminreview.print', encode($row->id)) . '" target="_blank" rel="noopener noreferrer" class="btn btn-secondary btn-sm" title="Cetak"><i class="material-icons-outlined">print</i></a>';
                }
                return $btn;
            })
            ->rawColumns(['audit_template_id', 'klausa', 'bil_juruaudit', 'status', 'created_by', 'updated_by', 'tindakan'])
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

            /*
            |--------------------------------------------------------------------------
            | PINDAAN KETUA KUMPULAN AUDIT
            |--------------------------------------------------------------------------
            */
            'answers.review.checklists',

            /*
            |--------------------------------------------------------------------------
            | RUMUSAN KETUA
            |--------------------------------------------------------------------------
            */
            'conclusion.files',

            /*
            |--------------------------------------------------------------------------
            | ULASAN ADMIN
            |--------------------------------------------------------------------------
            */
            'review',

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


        return view(
            'auditadminreview.create',
            compact(
                'auditGroup',
                'readonly'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */
        $request->validate([
            'audit_group_id' => 'required',
            'review' => 'required|string',
        ], [
            'audit_group_id.required' => 'Audit group tidak ditemukan.',
            'review.required' => 'Ulasan Admin wajib diisi.',
        ]);

        $groupId = decode($request->audit_group_id);

        /*
        |--------------------------------------------------------------------------
        | Audit Group
        |--------------------------------------------------------------------------
        */
        $auditGroup = AuditGroups::findOrFail($groupId);

        /*
        |--------------------------------------------------------------------------
        | Pastikan hanya Admin
        |--------------------------------------------------------------------------
        */
        if (!auth()->user()->hasRole('Admin')) {
            abort(403, 'Anda tidak dibenarkan memberikan ulasan audit.');
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan status group MENUNGGU ULASAN
        |--------------------------------------------------------------------------
        */
        if ($auditGroup->status !== 'MENUNGGU ULASAN') {

            return redirect()
                ->route('auditadminreview')
                ->with(
                    'error',
                    'Ulasan hanya boleh diberikan bagi audit yang sedang menunggu ulasan.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan / Update Ulasan Admin
        |--------------------------------------------------------------------------
        */
        $review = AuditAdminReviews::updateOrCreate(
            [
                'audit_group_id' => $groupId,
            ],
            [
                'review' => $request->review,
                'updated_by' => auth()->id(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Set created_by untuk record baru
        |--------------------------------------------------------------------------
        */
        if ($review->wasRecentlyCreated) {

            $review->created_by = auth()->id();
            $review->save();
        }

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */
        auditTrail(
            $review->wasRecentlyCreated ? 'Create' : 'Update',
            'Audit',
            'Admin Review',
            $review->id,
            'Ulasan Admin',
            auth()->id()
        );

        /*
        |--------------------------------------------------------------------------
        | Redirect balik ke page Admin Review
        |--------------------------------------------------------------------------
        */
        return redirect()
            ->route('auditadminreview.create', encode($groupId))
            ->with('success', 'Ulasan Admin berjaya disimpan.');
    }

    public function attachment(Request $request)
    {
        $request->validate([
            'ref_id' => 'required',
            'ref_type' => 'required|in:admin_review,review',
        ]);

        $refId = decode($request->ref_id);
        $refType = $request->ref_type;

        /*
        |--------------------------------------------------------------------------
        | Pastikan Admin Review wujud
        |--------------------------------------------------------------------------
        */
        $review = AuditAdminReviews::with('auditGroup')
            ->findOrFail($refId);

        /*
            |--------------------------------------------------------------------------
            | Pastikan audit masih MENUNGGU ULASAN
            |--------------------------------------------------------------------------
            */
        if ($review->auditGroup->status !== 'MENUNGGU ULASAN') {

            return response()->json([
                'success' => false,
                'message' => 'Lampiran tidak boleh ditambah kerana audit telah selesai.'
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan fail dipilih
        |--------------------------------------------------------------------------
        */
        if (!$request->hasFile('tfiles')) {

            return response()->json([
                'success' => false,
                'message' => 'Tiada fail dipilih.'
            ], 422);
        }

        $allowed = [
            'doc',
            'docx',
            'pdf',
            'txt',
            'jpeg',
            'png',
            'jpg',
            'gif',
            'svg'
        ];

        /*
        |--------------------------------------------------------------------------
        | Upload File
        |--------------------------------------------------------------------------
        */
        foreach ($request->file('tfiles') as $file) {

            $extension = strtolower(
                $file->getClientOriginalExtension()
            );

            /*
            |--------------------------------------------------------------------------
            | Semak extension
            |--------------------------------------------------------------------------
            */
            if (!in_array($extension, $allowed)) {

                return response()->json([
                    'success' => false,
                    'message' => 'Jenis fail tidak dibenarkan.'
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Maximum 10MB
            |--------------------------------------------------------------------------
            */
            if ($file->getSize() > 10485760) {

                return response()->json([
                    'success' => false,
                    'message' => 'Saiz fail melebihi 10MB.'
                ], 422);
            }

            $filename = $file->getClientOriginalName();

            $newName = uniqid('AUDIT_');

            /*
            |--------------------------------------------------------------------------
            | Folder Admin Review
            |--------------------------------------------------------------------------
            |
            | uploads/audit/admin_review/1/
            |
            */
            $path = $file->storeAs(
                'uploads/audit/' . $refType . '/' . $refId,
                $newName . '.' . $extension,
                'public'
            );

            /*
            |--------------------------------------------------------------------------
            | Simpan File
            |--------------------------------------------------------------------------
            */
            AuditFiles::create([
                'ref_id' => $refId,
                'ref_type' => $refType,
                'file_name_ori' => $filename,
                'file_name' => $newName,
                'file_path' => $path,
                'file_ext' => $extension,
                'created_by' => auth()->id(),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */
        auditTrail(
            'Upload',
            'Audit',
            'Admin Review Attachment',
            $refId,
            'Admin Review Attachment',
            auth()->id()
        );

        return response()->json([
            'success' => true,
            'message' => 'Lampiran berjaya dimuat naik.'
        ]);
    }

    public function listattachment(Request $request)
    {
        $request->validate([
            'ref_id' => 'required',
            'ref_type' => 'required|in:review',
        ]);

        $refId = decode($request->ref_id);
        $refType = $request->ref_type;

        $tfiles = AuditFiles::where('ref_id', $refId)
            ->where('ref_type', $refType)
            ->get();

        $datatable = Datatables::of($tfiles)
            ->addIndexColumn()

            ->addColumn('file_name', function ($row) {
                return $row->file_name_ori;
            })

            ->addColumn('created_at', function ($row) {
                return $row->created_at->format('d/m/Y H:i:s');
            })

            ->addColumn('tindakan', function ($row) use ($refType) {

                $btn = '';

                /*
                |--------------------------------------------------------------------------
                | Download sentiasa dibenarkan
                |--------------------------------------------------------------------------
                */
                $btn .= ' <a href="' . route('auditfiles.download', encode($row->id)) . '"
                            class="btn btn-success btn-sm"
                            title="Download">
                            <i class="material-icons-outlined">download</i>
                          </a>';

                /*
                |--------------------------------------------------------------------------
                | Attachment Ulasan Admin
                |--------------------------------------------------------------------------
                */
                if ($refType === 'review') {

                    $review = AuditAdminReviews::find($row->ref_id);

                    /*
                    |--------------------------------------------------------------------------
                    | Belum submit ulasan = masih boleh delete
                    |--------------------------------------------------------------------------
                    */
                    if ($review && is_null($review->submitted_at)) {

                        $btn .= ' <a href="' . route('auditadminreview.delete', encode($row->id)) . '"
                                    class="btn btn-danger btn-sm"
                                    title="Delete">
                                    <i class="material-icons-outlined">delete</i>
                                  </a>';
                    }
                }

                return $btn;
            })

            ->rawColumns([
                'file_name',
                'created_at',
                'tindakan'
            ])

            ->make(true);

        return $datatable;
    }

    public function delete($id)
    {
        $tfiles = AuditFiles::findOrFail(decode($id));

        $refId = $tfiles->ref_id;
        $fileName = $tfiles->file_name_ori;

        // Delete physical file
        if (Storage::disk('public')->exists($tfiles->file_path)) {
            Storage::disk('public')->delete($tfiles->file_path);
        }

        // Delete database record
        $tfiles->delete();

        auditTrail(
            'Delete',
            'Audit',
            'Attachment',
            $refId,
            $fileName,
            \Auth::user()->id
        );

        return redirect()
            ->back()
            ->with('success', 'Lampiran berjaya dihapus.');
    }

    public function submitReview(Request $request)
    {
        $request->validate([
            'review_id' => 'required',
        ]);

        $reviewId = decode($request->review_id);

        $review = AuditAdminReviews::with('auditGroup')
            ->findOrFail($reviewId);

        $auditGroup = $review->auditGroup;


        /*
        |--------------------------------------------------------------------------
        | Pastikan hanya Admin
        |--------------------------------------------------------------------------
        */
        if (!auth()->user()->hasRole('Admin')) {

            return response()->json([
                'success' => false,
                'message' => 'Hanya Admin dibenarkan menghantar ulasan.'
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan status Audit Group betul
        |--------------------------------------------------------------------------
        */
        if ($auditGroup->status !== 'MENUNGGU ULASAN') {

            return response()->json([
                'success' => false,
                'message' => 'Status audit tidak membenarkan ulasan dihantar.'
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan belum pernah submit
        |--------------------------------------------------------------------------
        */
        if ($review->submitted_at) {

            return response()->json([
                'success' => false,
                'message' => 'Ulasan Admin telah dihantar sebelum ini.'
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan ulasan mempunyai kandungan
        |--------------------------------------------------------------------------
        */
        if (blank(strip_tags($review->review))) {

            return response()->json([
                'success' => false,
                'message' => 'Sila lengkapkan ulasan terlebih dahulu.'
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Submit Admin Review
        |--------------------------------------------------------------------------
        */
        $review->submitted_at = now();
        $review->updated_by = auth()->id();
        $review->save();

        /*
        |--------------------------------------------------------------------------
        | Audit Group Selesai
        |--------------------------------------------------------------------------
        */
        $auditGroup->status = 'SELESAI';
        $auditGroup->completed_at = now();
        $auditGroup->updated_by = auth()->id();
        $auditGroup->save();

        /*
        |--------------------------------------------------------------------------
        | Audit Trail
        |--------------------------------------------------------------------------
        */
        auditTrail(
            'Submit',
            'Audit',
            'Admin Review',
            $review->id,
            'Ulasan audit dihantar oleh Admin',
            auth()->id()
        );

        return response()->json([
            'success' => true,
            'message' => 'Ulasan berjaya dihantar dan audit telah selesai.'
        ]);
    }

    public function print($id)
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

            /*
            |--------------------------------------------------------------------------
            | Jawapan asal Auditor
            |--------------------------------------------------------------------------
            */
            'answers.auditor',
            'answers.checklists',

            /*
            |--------------------------------------------------------------------------
            | Pindaan Ketua Kumpulan
            |--------------------------------------------------------------------------
            */
            'answers.review.checklists',

            /*
            |--------------------------------------------------------------------------
            | Rumusan Ketua & Ulasan Admin
            |--------------------------------------------------------------------------
            */
            'conclusion',
            'review',

        ])->findOrFail($groupId);


        /*
        |--------------------------------------------------------------------------
        | Hanya audit SELESAI boleh dicetak
        |--------------------------------------------------------------------------
        */
        if ($auditGroup->status !== 'SELESAI') {

            return redirect()
                ->route('auditadminreview')
                ->with(
                    'error',
                    'Laporan hanya boleh dicetak selepas audit selesai.'
                );
        }


        return view(
            'auditadminreview.print',
            compact('auditGroup')
        );
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
