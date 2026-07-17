<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user.index');
    }

    public function list(Request $request)
    {
        $data = User::whereDoesntHave('roles', function ($query) {
                    $query->where('name', 'Admin');
                })->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('roles', function ($row) {
                return $row->roles->pluck('name')->implode(', ');
            })
            ->addColumn('status', function ($row) {

                $btn = '<div class="d-flex justify-content-center align-items-center">';

                if ($row->status == 'AKTIF') {

                    $btn .= '<a href="'.route('user.actionStatus',[encode($row->id),0]).'"
                                class="btn btn-success btn-circle raised rounded-circle wh-48"
                                title="Aktif">
                                <i class="material-icons-outlined">toggle_on</i>
                            </a>';

                } else {

                    $btn .= '<a href="'.route('user.actionStatus',[encode($row->id),1]).'"
                                class="btn btn-danger btn-circle raised rounded-circle wh-48"
                                title="Tidak Aktif">
                                <i class="material-icons-outlined">toggle_off</i>
                            </a>';

                }

                $btn .= '</div>';

                return $btn;
            })
            ->addColumn('tindakan', function ($row) {
                $btn = '';
                $btn .= '<a href="'.route('user.edit', encode($row->id)).'" class="btn btn-warning btn-sm">Edit</a>';
                $btn .= ' <a href="'.route('user.destroy', encode($row->id)).'" class="btn btn-danger btn-sm">Delete</a>';
                return $btn;
            })
            ->rawColumns(['status', 'tindakan'])
            ->make(true);
    }

    public function actionStatus($id, $status)
    {
        $user = User::find(decode($id));
        if ($status == 0) {
            $user->status = 'TIDAK AKTIF';
        } else {
            $user->status = 'AKTIF';
        }
        $user->save();
        auditTrail('Update', 'Pengguna', 'Pengguna', $user->id, $user->name, \Auth::user()->id);
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required',
            'password' => 'required',
            'role' => 'required',
        ], [
            'name.required' => 'Nama wajib diisi',
            'password.required' => 'Password wajib diisi',
            'role.required' => 'Role pengguna wajib diisi',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_pekerja' => $request->no_pekerja,
            'jawatan' => $request->jawatan,
            'jabatan' => $request->jabatan,
            'status' => 'AKTIF',
        ]);
        $role = Role::findById($request->role);
        $user->assignRole($role);
        auditTrail('Create', 'Pengguna', 'Pengguna', $user->id, $user->name, \Auth::user()->id);
        return redirect()->route('user')->with('success', 'Pengguna berjaya disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::find(decode($id));
        $roles = Role::all();
        return view('user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $validated = $request->validate([
            'no_pekerja' => 'required',
            'name' => 'required',
            'password' => 'required',
            'role' => 'required',
        ], [
            'no_pekerja.required' => 'No. Pekerja wajib diisi',
            'name.required' => 'Nama wajib diisi',
            'password.required' => 'Password wajib diisi',
            'role.required' => 'Role pengguna wajib diisi',
        ]);
        
        $user = User::find(decode($id));
        $user->name = $request->name;
        $user->email = $request->email;
        $user->no_pekerja = $request->no_pekerja;
        $user->jawatan = $request->jawatan;
        $user->jabatan = $request->jabatan;
        // Jika password diisi, baru hash dan simpan
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->save();
        $role = Role::findById($request->role);
        $user->syncRoles($role);
        auditTrail('Update', 'Pengguna', 'Pengguna', $user->id, $user->name, \Auth::user()->id);
        return redirect()->route('user')->with('success', 'Pengguna '.$user->name.' berjaya dikemaskini');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find(decode($id));
        $user->delete();
        return redirect()->route('user')->with('success', 'Pengguna '.$user->name.' berjaya dipadam');
    }
}
