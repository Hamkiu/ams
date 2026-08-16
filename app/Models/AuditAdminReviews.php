<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditAdminReviews extends Model
{
    protected $table = 'audit_admin_reviews';

    protected $fillable = [
        'audit_group_id',
        'review',
        'created_by',
        'updated_by',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function auditGroup()
    {
        return $this->belongsTo(AuditGroups::class, 'audit_group_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function useru()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
    public function files()
    {
        return $this->hasMany(AuditFiles::class, 'ref_id', 'id')->where('ref_type', 'review');
    }
}
