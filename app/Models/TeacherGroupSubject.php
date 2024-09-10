<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherGroupSubject extends Model
{
    use HasFactory;
    use CrudTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'teacher_subject_id',
        'group_id',
    ];

    protected $table = 'teacher_group_subjects';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'teacher_subject_id' => 'integer',
        'group_id' => 'integer',
    ];

    protected $appends = ['full_name'];

    public function teacherSubject(): BelongsTo
    {
        return $this->belongsTo(TeacherSubject::class, 'teacher_subject_id', 'id');
    }

    public function teacher(): BelongsTo
    {
        return $this->teacherSubject->teacher();
    }

    public function subject(): BelongsTo
    {
        return $this->teacherSubject->subject();
    }


    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->teacher_subject_id . ' - ' . $this->teacherSubject->subject->name;
    }
}
