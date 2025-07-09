@props([
    'publicid',
    'cloud' => env('CLOUDINARY_CLOUD_NAME'),
    'location',
    'path'
])

@php
    $thumbnailUrl = "https://res.cloudinary.com/{$cloud}/video/upload/{$location}/{$publicid}.jpg";
@endphp

<div class="relative group rounded-lg overflow-hidden shadow hover:shadow-lg transition-all duration-300">
    
    <img 
        src="{{ $thumbnailUrl }}" 
        alt="Video thumbnail" 
        class="w-full h-48 object-cover" 
        onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
    />

    <button class="w-8 h-8 absolute top-2 right-2 bg-black/50 hover:bg-black/70 text-white rounded-full p-1 hidden group-hover:block">
        ✎
    </button>

</div>
