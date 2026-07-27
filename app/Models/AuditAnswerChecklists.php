<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditAnswerChecklists extends Model
{
    protected $table = 'audit_answer_checklists';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $fillable = [
        'audit_answer_id',
        'audit_checklist_id',
    ];
    public function auditAnswer()
    {
        return $this->belongsTo(AuditAnswers::class, 'audit_answer_id', 'id');
    }
    public function auditChecklist()
    {
        return $this->belongsTo(AuditItemChecklist::class, 'audit_checklist_id', 'id');
    }
}
