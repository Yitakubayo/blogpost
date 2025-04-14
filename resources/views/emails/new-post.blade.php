<x-mail::message>
# New Post: {{ $post->title }}

A new post has been published on our blog!

<x-mail::button :url="route('posts.show', $post)">
Read More
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message> 