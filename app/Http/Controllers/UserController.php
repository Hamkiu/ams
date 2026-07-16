<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

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
        $data = User::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('roles', function ($row) {
                return $row->roles->pluck('name')->implode(', ');
            })
            ->addColumn('status', function ($row) {
                $btn = '';
                if ($row->status == 'AKTIF') {
                    $btn .= '<span class="badge bg-success">'.$row->status.'</span>';
                } else {
                    $btn .= '<span class="badge bg-danger">'.$row->status.'</span>';
                }
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
