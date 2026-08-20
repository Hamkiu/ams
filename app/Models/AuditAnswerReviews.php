<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditAnswerReviews extends Model
{
    protected $table = 'audit_answer_reviews';

    protected $primaryKey = 'id';

    protected $fillable = [
        'audit_answer_id',
        'penemuan_lain',
        'bukti_audit',
        'created_by',
        'updated_by',
    ];

    public function auditAnswer()
    {
        return $this->belongsTo(
            AuditAnswers::class,
            'audit_answer_id',
            'id'
        );
    }

    public function checklists()
    {
        return $this->hasMany(
            AuditAnswerReviewChecklists::class,
            'audit_answer_review_id',
            'id'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'created_by',
            'id'
        );
    }

    public function useru()
    {
        return $this->belongsTo(
            User::class,
            'updated_by',
            'id'
        );
    }
}
