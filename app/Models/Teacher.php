<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Teacher extends Model
{
    use CrudTrait;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
    ];

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class);
    }
    public function subjectsForGroup($groupId)
    {
        $group = Group::find($groupId);
        if ($group) {
            $specialtyId = $group->specialty_id;
            return $this->subjects()->where('specialty_id', $specialtyId)->get();
        }
        return collect();
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function groups()
    {
        return $this->hasManyThrough(Group::class, Subject::class, 'specialty_id', 'specialty_id', 'id', 'id');
    }
}
