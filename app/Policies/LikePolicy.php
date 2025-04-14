<?php

namespace App\Policies;

use App\Models\Like;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LikePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create likes.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can like
    }

    /**
     * Determine whether the user can delete the like.
     */
    public function delete(User $user, Like $like): bool
    {
        return $user->id === $like->user_id; // Only the user who created the like can delete it
    }
} 