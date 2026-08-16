<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Idea extends Model
{
    use HasFactory;

    public const CATEGORIES = ['Feature', 'Improvement', 'Question', 'Discussion'];

    public const STATUSES = ['open', 'planned', 'completed'];

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /** Has the given user already voted for this idea? */
    public function isVotedBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        // Set by withExists() on list queries, so a page of ideas costs one query.
        if (isset($this->attributes['voted_by_user'])) {
            return (bool) $this->attributes['voted_by_user'];
        }

        return $this->votes()->where('user_id', $user->id)->exists();
    }
}
