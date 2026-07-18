<?php

use App\Models\User;
use App\Models\AuditTemplate;

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
    $trail->push( $auditTemplate->id, route('audittemplate.items', encode($id)));
});