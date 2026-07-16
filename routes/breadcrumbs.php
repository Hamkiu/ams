<?php

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

// Audit Trail
Breadcrumbs::for('audittrail', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Audit Log', route('audittrail'));
});