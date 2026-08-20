<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditAnswerReviewChecklists extends Model
{
    protected $table = 'audit_answer_review_checklists';

    protected $primaryKey = 'id';

    protected $fillable = [
        'audit_answer_review_id',
        'audit_checklist_id',
        'status',
    ];

    public function auditAnswerReview()
    {
        return $this->belongsTo(
            AuditAnswerReviews::class,
            'audit_answer_review_id',
            'id'
        );
    }

    public function auditChecklist()
    {
        return $this->belongsTo(
            AuditItemChecklist::class,
            'audit_checklist_id',
            'id'
        );
    }
}
