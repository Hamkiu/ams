<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditGroupsMembers extends Model
{
    protected $table = 'audit_groups_members';
    protected $primaryKey = 'id';
    protected $fillable = ['audit_group_id', 'user_id', 'jabatan', 'sort', 'role', 'remarks', 'status', 'started_at', 'completed_at', 'created_by', 'updated_by'];
    public $timestamps = true;
    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function auditGroup()
    {
        return $this->belongsTo(AuditGroups::class, 'audit_group_id', 'id');
    }
    public function pengguna()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function useru()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
