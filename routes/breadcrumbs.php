<?php

// Home
Breadcrumbs::for('dashboard', function ($trail) {
    $trail->push('Home', route('dashboard'));
});

// Audit Trail
Breadcrumbs::for('audittrail', function ($trail) {
    $trail->parent('dashboard');
    $trail->push('Audit Log', route('audittrail'));
});