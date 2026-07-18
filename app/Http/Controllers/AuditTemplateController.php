<?php

namespace App\Http\Controllers;

use App\Models\AuditTemplate;
use Illuminate\Http\Request;

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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AuditTemplate $auditTemplate)
    {
        //
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
    public function destroy(AuditTemplate $auditTemplate)
    {
        //
    }
}
