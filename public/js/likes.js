document.addEventListener('DOMContentLoaded', function() {
    const likeButtons = document.querySelectorAll('.like-button');
    
    likeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const likeableType = this.dataset.likeableType;
            const likeableId = this.dataset.likeableId;
            
            fetch('/likes/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    likeable_type: likeableType,
                    likeable_id: likeableId
                })
            })
            .then(response => response.json())
            .then(data => {
                // Update like button state
                const icon = this.querySelector('i');
                if (data.liked) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    this.classList.add('text-red-500');
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    this.classList.remove('text-red-500');
                }
                
                // Update likes count
                const likesCount = this.querySelector('.likes-count');
                if (likesCount) {
                    likesCount.textContent = data.likes_count;
                }
                
                // Show success message
                if (data.message) {
                    const toast = document.createElement('div');
                    toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg';
                    toast.textContent = data.message;
                    document.body.appendChild(toast);
                    setTimeout(() => toast.remove(), 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow-lg';
                toast.textContent = 'An error occurred while processing your request.';
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            });
        });
    });
}); 