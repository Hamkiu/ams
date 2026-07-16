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
            ->addColumn('tindakan', function ($row) {
                $btn = '';
                $btn .= '<a href="'.route('user.edit', encode($row->id)).'" class="btn btn-warning btn-sm">Edit</a>';
                return $btn;
            })
            ->rawColumns(['tindakan'])
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
        //
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
    public function destroy(string $id)
    {
        //
    }
}
