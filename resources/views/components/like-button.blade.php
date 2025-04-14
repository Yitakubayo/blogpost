@props(['likeableType', 'likeableId', 'likesCount' => 0, 'isLiked' => false])

<button 
    class="like-button flex items-center space-x-1 text-gray-500 hover:text-red-500 transition-colors duration-200 {{ $isLiked ? 'text-red-500' : '' }}"
    data-likeable-type="{{ $likeableType }}"
    data-likeable-id="{{ $likeableId }}"
    {{ $attributes }}
>
    <i class="{{ $isLiked ? 'fas' : 'far' }} fa-heart"></i>
    @if($likesCount > 0)
        <span class="likes-count text-sm">{{ $likesCount }}</span>
    @endif
</button> 