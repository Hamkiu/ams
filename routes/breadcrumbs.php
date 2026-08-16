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
    $trail->push('Edit Pengguna', route('user.edit', $id));
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

// Audit Template Preview
Breadcrumbs::for('audittemplate.preview', function ($trail, $id) {
    $auditTemplate = AuditTemplate::find(decode($id));
    $trail->parent('audittemplate');
    $trail->push($auditTemplate->id, route('audittemplate.preview', $id));
});

// Audit Template Items
Breadcrumbs::for('audittemplate.items', function ($trail, $id) {
    $auditTemplate = AuditTemplate::find(decode($id));
    $trail->parent('audittemplate');
    $trail->push($auditTemplate->id, route('audittemplate.items', $id));
});

// Audit Template Checklist
Breadcrumbs::for('audittemplate.checklist', function ($trail, $id) {
    $auditTemplateItems = AuditTemplateItems::find(decode($id));
    $trail->parent('audittemplate.items', encode($auditTemplateItems->audit_template_id));
    $trail->push($auditTemplateItems->perkara, route('audittemplate.checklist', $id));
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

// Audit Group Answers
Breadcrumbs::for('auditgroup.answers', function ($trail, $id) {
    $auditGroup = AuditGroups::find(decode($id));
    $trail->parent('auditgroup');
    $trail->push($auditGroup->name, route('auditgroup.answers', $id));
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

// Audit Summary
Breadcrumbs::for('audit.summary', function ($trail, $id) {
    $auditGroup = AuditGroups::find(decode($id));
    $trail->parent('audit');
    $trail->push($auditGroup->name, route('audit.summary', $id));
});

// Audit Admin Review
Breadcrumbs::for('auditadminreview', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Audit Admin Review', route('auditadminreview'));
});

// Audit Admin Review Create
Breadcrumbs::for('auditadminreview.create', function ($trail, $id) {
    $auditGroup = AuditGroups::find(decode($id));
    $trail->parent('auditadminreview');
    $trail->push($auditGroup->name, route('auditadminreview.create', $id));
});
