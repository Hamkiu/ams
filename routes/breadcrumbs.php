<?php

use App\Models\User;
use App\Models\AuditTemplate;
use App\Models\AuditTemplateItems;
use App\Models\AuditGroups;

// Home
Breadcrumbs::for('dashboard', function ($trail) {
    $trail->push('Home', route('dashboard'));
});

// User
Breadcrumbs::for('user', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Pengguna', route('user'));
});

Breadcrumbs::for('user.create', function ($trail) {
    $trail->parent('user');
    $trail->push('Tambah Pengguna', route('user.create'));
});

Breadcrumbs::for('user.edit', function ($trail, $id) {
    $trail->parent('user');
    $trail->push('Edit Pengguna',route('user.edit', $id));
});

// Audit Trail
Breadcrumbs::for('audittrail', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Audit Log', route('audittrail'));
});

// Audit Template
Breadcrumbs::for('audittemplate', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Audit Template', route('audittemplate'));
});

// Audit Template Items
Breadcrumbs::for('audittemplate.items', function ($trail, $id) {
    $auditTemplate = AuditTemplate::find(decode($id));
    $trail->parent('audittemplate');
    $trail->push( $auditTemplate->id, route('audittemplate.items', $id));
});

// Audit Template Checklist
Breadcrumbs::for('audittemplate.checklist', function ($trail, $id) {
    $auditTemplateItems = AuditTemplateItems::find(decode($id));
    $trail->parent('audittemplate.items', encode($auditTemplateItems->audit_template_id));
    $trail->push( $auditTemplateItems->perkara, route('audittemplate.checklist', $id));
});

// Audit Group
Breadcrumbs::for('auditgroup', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Audit Group', route('auditgroup'));
});

// Audit Group Create
Breadcrumbs::for('auditgroup.create', function ($trail) {
    $trail->parent('auditgroup');
    $trail->push('Tambah Group', route('auditgroup.create'));
});

// Audit Group Edit
Breadcrumbs::for('auditgroup.edit', function ($trail, $id) {
    $auditGroup = AuditGroups::find(decode($id));
    $trail->parent('auditgroup');
    $trail->push($auditGroup->name, route('auditgroup.edit', $id));
});

// Audit
Breadcrumbs::for('audit', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Senarai Audit', route('audit'));
});

// Audit Show
Breadcrumbs::for('audit.show', function ($trail, $id) {
    $auditGroup = AuditGroups::find(decode($id));
    $trail->parent('audit');
    $trail->push($auditGroup->name, route('audit.show', $id));
});