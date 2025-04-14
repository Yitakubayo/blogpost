<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $post->title }}
            </h2>
            @can('update', $post)
                <div class="flex space-x-2">
                    <a href="{{ route('posts.edit', $post) }}" 
                       class="inline-flex items-center px-3 py-1 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-3 py-1 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600"
                                onclick="return confirm('Are you sure you want to delete this post?')">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>
                    </form>
                </div>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($post->image)
                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="w-full h-96 object-cover rounded-lg mb-6">
                    @endif
                    
                    <div class="prose max-w-none">
                        {!! nl2br(e($post->description)) !!}
                    </div>

                    <div class="mt-6 flex items-center justify-between text-sm text-gray-500">
                        <div>
                            By {{ $post->user->name }} on {{ $post->created_at->format('F j, Y') }}
                        </div>
                        <div class="flex items-center space-x-4">
                            <x-like-button 
                                :likeableType="'App\\Models\\Post'" 
                                :likeableId="$post->id" 
                                :likesCount="$post->likes->count()" 
                                :isLiked="$post->likes->contains('user_id', auth()->id())"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold mb-4">Comments</h3>
                
                @auth
                    <form action="{{ route('posts.comments.store', $post) }}" method="POST" class="mb-6">
                        @csrf
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700">Add a comment</label>
                            <textarea name="content" id="content" rows="3" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                      required></textarea>
                        </div>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600">
                            Post Comment
                        </button>
                    </form>
                @endauth

                <div class="space-y-6">
                    @foreach($post->comments as $comment)
                        <div class="bg-white p-4 rounded-lg shadow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm text-gray-500">
                                        {{ $comment->user->name }} on {{ $comment->created_at->format('F j, Y g:i a') }}
                                    </p>
                                    <p class="mt-2">{{ $comment->content }}</p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <x-like-button 
                                        :likeableType="'App\\Models\\Comment'" 
                                        :likeableId="$comment->id" 
                                        :likesCount="$comment->likes->count()" 
                                        :isLiked="$comment->likes->contains('user_id', auth()->id())"
                                    />
                                    @can('delete', $comment)
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-1 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600"
                                                    onclick="return confirm('Are you sure you want to delete this comment?')">
                                                <i class="fas fa-trash mr-1"></i> Delete
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function toggleLike(type, id) {
            fetch('/likes/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    likeable_type: type === 'post' ? 'App\\Models\\Post' : 'App\\Models\\Comment',
                    likeable_id: id
                })
            })
            .then(response => response.json())
            .then(data => {
                // Update like count and icon
                const likeButton = event.currentTarget;
                const likeCount = likeButton.querySelector('span');
                const likeIcon = likeButton.querySelector('i');
                
                likeCount.textContent = parseInt(likeCount.textContent) + (data.liked ? 1 : -1);
                likeIcon.classList.toggle('far');
                likeIcon.classList.toggle('fas');
            });
        }

        function showReplyForm(commentId) {
            const form = document.getElementById(`reply-form-${commentId}`);
            form.classList.toggle('hidden');
        }
    </script>
    @endpush
</x-app-layout> 