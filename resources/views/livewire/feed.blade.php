<div 
    x-data="{ 
        init() {
            window.addEventListener('scroll', () => {
                let scrollBottom = window.innerHeight + window.scrollY;
                if (scrollBottom >= document.body.offsetHeight - 100) {
                    $wire.loadMore();
                }
            });
        }
    }" 
    x-init="init()"
    class="space-y-4"
>
    @foreach ($posts as $post)
        <x-post :post="$post" back="feed" />
    @endforeach

    @if ($hasMore)
        <div class="p-4 text-center text-gray-500">Loading more posts...</div>
    @else
        <div class="p-4 text-center text-gray-400">You’ve reached the end.</div>
    @endif
</div>
