<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditFiles extends Model
{
    protected $table = 'audit_files';
    protected $primaryKey = 'id';
    protected $appends = ['encrypt_id'];
    protected $fillable = [
        'ref_id',
        'file_name_ori',
        'file_name',
        'file_path',
        'file_ext',
    ];

    public $incrementing = true;

    public function getEncryptIdAttribute()
    {
        return encrypt($this->id);
    }
}
