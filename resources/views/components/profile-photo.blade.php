@if ($path)
    <x-cloudinary::image 
        public-id="{{ $path }}" 
        width="{{ $width }}" 
        height="{{ $height }}" 
        class="{{ $class }}" 
        alt="{{ $alt }}" 
    />
@else
    <img 
        src="https://ui-avatars.com/api/?name={{ urlencode($alt) }}&size={{ $width }}" 
        width="{{ $width }}" 
        height="{{ $height }}" 
        class="{{ $class }}" 
        alt="{{ $alt }}" 
    />
@endif
