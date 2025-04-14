<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewPostNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Post: ' . $this->post->title)
            ->greeting('Hello!')
            ->line('A new post has been published on our blog.')
            ->line('Title: ' . $this->post->title)
            ->line(Str::limit($this->post->description, 200))
            ->action('Read More', route('posts.show', $this->post))
            ->line('Thank you for subscribing to our blog!');
    }
} 