<div class="space-y-4">
    @foreach ($posts as $post)
        <x-post :post="$post" />
    @endforeach
</div>
