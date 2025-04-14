<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Posts') }}
            </h2>
            @auth
                <a href="{{ route('posts.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600">
                    <i class="fas fa-plus mr-2"></i> Create Post
                </a>
            @endauth
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($posts as $post)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-300">
                        <a href="{{ route('posts.show', $post) }}" class="block">
                            @if($post->image)
                                <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                            @endif
                            <div class="p-6">
                                <h3 class="text-lg font-semibold mb-2 text-gray-900 hover:text-blue-600">
                                    {{ $post->title }}
                                </h3>
                                <p class="text-gray-600 mb-4">
                                    {{ Str::limit($post->description, 150) }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex items-center text-gray-500">
                                            <i class="far fa-comment mr-1"></i>
                                            <span>{{ $post->comments->count() }}</span>
                                        </div>
                                        <x-like-button 
                                            :likeableType="'App\\Models\\Post'" 
                                            :likeableId="$post->id" 
                                            :likesCount="$post->likes->count()" 
                                            :isLiked="$post->likes->contains('user_id', auth()->id())"
                                        />
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        By {{ $post->user->name }}
                                    </div>
                                </div>
                            </div>
                        </a>
                        
                        @can('update', $post)
                            <div class="px-6 pb-6 flex space-x-2">
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
                @endforeach
            </div>

            <div class="mt-6">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-app-layout> 