<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditItemChecklist extends Model
{
    protected $table = 'audit_item_checklists';
    protected $primaryKey = 'id';
    protected $fillable = [
        'items_id',
        'name',
        'created_by',
        'updated_by',
    ];

    public function items()
    {
        return $this->belongsTo(AuditTemplateItems::class, 'items_id', 'id');
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
