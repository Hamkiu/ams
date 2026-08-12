<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditAnswers extends Model
{
    protected $table = 'audit_answers';
    protected $primaryKey = 'id';
    protected $fillable = [
        'audit_group_id',
        'audit_item_id',
        'user_id',
        'penemuan_lain',
        'bukti_audit',
        'created_by',
        'updated_by',
    ];
    public function auditGroup()
    {
        return $this->belongsTo(AuditGroups::class, 'audit_group_id', 'id');
    }
    public function auditItem()
    {
        return $this->belongsTo(AuditTemplateItems::class, 'audit_item_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function useru()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
    public function auditor()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function checklists()
    {
        return $this->hasMany(AuditAnswerChecklists::class, 'audit_answer_id', 'id');
    }
    public function member()
    {
        return $this->belongsTo(
            AuditGroupsMembers::class,
            'user_id',
            'user_id'
        );
    }
    public function isCompleted()
    {
        $hasChecklist = $this->auditItem->checklists()->exists();

        if ($hasChecklist) {

            return !empty($this->bukti_audit)
                && $this->checklists()->exists();
        }

        return !empty($this->bukti_audit);
    }

    public function files()
    {
        return $this->hasMany(AuditFiles::class, 'ref_id', 'id');
    }
}
