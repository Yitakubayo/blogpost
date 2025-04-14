<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\EmailSubscription;
use App\Notifications\NewPostNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewPostNotification as MailNewPostNotification;

class PostController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $posts = Post::with(['user', 'comments', 'likes'])
            ->latest()
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        $post->load(['user', 'comments.user', 'comments.replies.user', 'likes']);
        return view('posts.show', compact('post'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        $post = auth()->user()->posts()->create($validated);

        // Notify all subscribers about the new post
        $subscribers = EmailSubscription::where('is_active', true)->get();
        foreach ($subscribers as $subscriber) {
            $subscriber->notify(new NewPostNotification($post));
        }

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post created successfully!');
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $validated['image'] = $request->file('image')->store('posts', 'public');
        }

        $post->update($validated);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully!');
    }

    public function share(Post $post, $platform)
    {
        $url = route('posts.show', $post);
        $title = $post->title;
        $description = Str::limit($post->description, 100);

        switch ($platform) {
            case 'facebook':
                return redirect()->away("https://www.facebook.com/sharer/sharer.php?u={$url}");
            case 'twitter':
                return redirect()->away("https://twitter.com/intent/tweet?text={$title}&url={$url}");
            case 'whatsapp':
                return redirect()->away("https://wa.me/?text={$title}%20{$url}");
            case 'instagram':
                return back()->with('success', 'Copy this URL to share on Instagram: ' . $url);
            default:
                return back()->with('error', 'Invalid sharing platform');
        }
    }

    /**
     * Notify subscribers about a new post
     */
    protected function notifySubscribers(Post $post)
    {
        $subscribers = EmailSubscription::where('is_active', true)->get();
        
        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)
                ->queue(new MailNewPostNotification($post));
        }
    }
} 