<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Topic extends Model
{
    use CrudTrait;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number',
        'subject_id',
        'name',
        'hours',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'subject_id' => 'integer',
    ];

//    protected static function boot()
//    {
//        parent::boot();
//
//        static::creating(function ($topic) {
//            $topic->number = Topic::where('subject_id', $topic->subject_id)->max('number') + 1;
//        });
//    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
