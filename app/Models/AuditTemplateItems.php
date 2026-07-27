<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditTemplateItems extends Model
{
    protected $table = 'audit_template_items';
    protected $primaryKey = 'id';
    protected $fillable = [
        'audit_template_id',
        'sort',
        'perkara',
        'no_klausa',
        'klausa',
        'keterangan',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function template()
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

    public function checklists()
    {
        return $this->hasMany(AuditItemChecklist::class, 'items_id', 'id');
    }
    public function answers()
    {
        return $this->hasMany(AuditAnswers::class, 'audit_item_id', 'id');
    }
}
