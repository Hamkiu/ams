<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AuditTrail extends Model
{
    protected $table = 'audit_trails';
    protected $primaryKey = 'audit_trail_id';
    protected $appends = ['encrypt_id'];
    protected $fillable = [
        'audit_trail_module', 'audit_trail_component', 'audit_trail_action', 'audit_trail_data_id', 'audit_trail_data_name', 'audit_trail_desc', 'users_id'
    ];

    public $incrementing = false;

    public function getEncryptIdAttribute()
	{
		return encrypt($this->audit_trail_id) ;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}
