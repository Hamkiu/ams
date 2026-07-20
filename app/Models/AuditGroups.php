<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditGroups extends Model
{
    protected $table = 'audit_groups';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id',
        'audit_template_id',
        'name',
        'jabatan',
        'tarikh',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tarikh' => 'date',
    ];

    public function getEncryptIdAttribute()
	{
		return encrypt($this->id) ;
    }

    public function auditTemplate()
    {
        return $this->belongsTo(AuditTemplate::class, 'audit_template_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function useru()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function members()
    {
        return $this->hasMany(AuditGroupsMembers::class, 'audit_group_id', 'id');
    }
}
