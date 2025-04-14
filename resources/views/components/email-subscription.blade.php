<div class="bg-gray-50 p-6 rounded-lg">
    <h3 class="text-lg font-semibold mb-4">Subscribe to Our Newsletter</h3>
    <p class="text-gray-600 mb-4">Get notified about new posts and updates.</p>
    
    <form action="{{ route('subscribe') }}" method="POST" class="flex gap-2">
        @csrf
        <input type="email" name="email" placeholder="Enter your email" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Subscribe
        </button>
    </form>

    @if(session('success'))
        <p class="mt-2 text-sm text-green-600">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p class="mt-2 text-sm text-red-600">{{ session('error') }}</p>
    @endif
</div> 