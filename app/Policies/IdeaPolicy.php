<?php

namespace App\Policies;

use App\Models\Idea;
use App\Models\User;

class IdeaPolicy
{
    // Any authenticated user may view ideas (it's a forum),
    // so no view() method is needed here.

    public function update(User $user, Idea $idea): bool
    {
        return $idea->user_id === $user->id;
    }

    public function delete(User $user, Idea $idea): bool
    {
        return $idea->user_id === $user->id;
    }
}
