<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

class TeacherSubject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'subject_id',
        'teacher_id',
    ];

    protected $table = 'teacher_subject';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'subject_id' => 'integer',
        'teacher_id' => 'integer',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    protected $appends = ['full_name', 'teacher_full_name', 'item_name'];

    public function getFullNameAttribute(): string
    {
        return $this->teacher->fullname. ' - ' . $this->subject->name;
    }

    public function getTeacherFullNameAttribute(): string
    {
        return $this->teacher->fullname;
    }

    public function getItemNameAttribute(): string
    {
        return $this->subject->name;
    }

    public function isColumnNullable($column)
    {
        $table = $this->getTable();
        $connection = Schema::getConnection()->getDoctrineSchemaManager();
        $columns = $connection->listTableColumns($table);

        if (array_key_exists($column, $columns)) {
            return !$columns[$column]->getNotnull();
        }

        return false;
    }
}
