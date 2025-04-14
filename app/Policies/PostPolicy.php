<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(?User $user)
    {
        return true; // Anyone can view posts
    }

    public function view(?User $user, Post $post)
    {
        return true; // Anyone can view a post
    }

    public function create(User $user)
    {
        return true; // Any authenticated user can create posts
    }

    public function update(User $user, Post $post)
    {
        return $user->id === $post->user_id || $user->is_admin;
    }

    public function delete(User $user, Post $post)
    {
        return $user->id === $post->user_id || $user->is_admin;
    }
} 