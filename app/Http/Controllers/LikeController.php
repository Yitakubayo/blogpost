<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggleLike(Request $request)
    {
        $validated = $request->validate([
            'likeable_type' => 'required|in:App\\Models\\Post,App\\Models\\Comment',
            'likeable_id' => 'required|integer'
        ]);

        $likeable = $validated['likeable_type'] === 'App\\Models\\Post'
            ? Post::findOrFail($validated['likeable_id'])
            : Comment::findOrFail($validated['likeable_id']);

        $like = $likeable->likes()
            ->where('user_id', Auth::id())
            ->first();

        if ($like) {
            $this->authorize('delete', $like);
            $like->delete();
            $message = $validated['likeable_type'] === 'App\\Models\\Post' ? 'Post unliked successfully' : 'Comment unliked successfully';
            return response()->json([
                'liked' => false,
                'message' => $message,
                'likes_count' => $likeable->likes()->count()
            ]);
        }

        $this->authorize('create', Like::class);
        $likeable->likes()->create([
            'user_id' => Auth::id()
        ]);

        $message = $validated['likeable_type'] === 'App\\Models\\Post' ? 'Post liked successfully' : 'Comment liked successfully';
        return response()->json([
            'liked' => true,
            'message' => $message,
            'likes_count' => $likeable->likes()->count()
        ]);
    }

    public function getLikesCount(Request $request)
    {
        $validated = $request->validate([
            'likeable_type' => 'required|in:App\\Models\\Post,App\\Models\\Comment',
            'likeable_id' => 'required|integer'
        ]);

        $likeable = $validated['likeable_type'] === 'App\\Models\\Post'
            ? Post::findOrFail($validated['likeable_id'])
            : Comment::findOrFail($validated['likeable_id']);

        return response()->json([
            'likes_count' => $likeable->likes()->count(),
            'is_liked' => $likeable->likes()->where('user_id', Auth::id())->exists()
        ]);
    }
} 