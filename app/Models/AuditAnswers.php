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

    public function review()
    {
        return $this->hasOne(
            AuditAnswerReviews::class,
            'audit_answer_id',
            'id'
        );
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
        if (empty($this->bukti_audit)) {
            return false;
        }

        $totalChecklist = $this->auditItem
            ->checklists()
            ->count();

        // Item tiada checklist
        if ($totalChecklist === 0) {
            return true;
        }

        $totalAnswered = $this->checklists()
            ->whereNotNull('status')
            ->count();

        return $totalAnswered === $totalChecklist;
    }

    public function files()
    {
        return $this->hasMany(AuditFiles::class, 'ref_id', 'id')->where('ref_type', 'answer');
    }
}
