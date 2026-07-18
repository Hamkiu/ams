<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditTemplate extends Model
{
    protected $table = 'audit_templates';
    protected $primaryKey = 'id';
    protected $appends = ['encrypt_id'];
    protected $fillable = [
        'name',
        'no_rujukan',
        'no_pindaan',
        'version',
        'description',
        'status',
        'tarikh_berkuatkuasa',
        'created_by',
        'updated_by',
    ];

    public $incrementing = false;

    public function getEncryptIdAttribute()
	{
		return encrypt($this->id) ;
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
