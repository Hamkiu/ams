<?php

namespace App\Http\Controllers;

use App\Models\AuditGroups;
use App\Models\AuditGroupsMembers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index()
    {

        $groups = AuditGroupsMembers::with([
            'auditGroup.auditTemplate',
            'auditGroup.members.pengguna'
        ])
            ->where('user_id', Auth::id())
            ->get();

        return view('audit.index', compact('groups'));
    }

    public function show($id)
    {
        $group = AuditGroups::with([
            'auditTemplate.items.checklists',
            'members.pengguna'
        ])
            ->find(decode($id));

        return view('audit.show', compact('group'));
    }

    public function store(Request $request)
    {
        dd($request->all());
    }
}
