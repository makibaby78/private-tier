<div
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/80"
    @keydown.escape.window="open = false"
    x-init="
        let startX = 0;
        $el.addEventListener('touchstart', e => startX = e.touches[0].clientX);
        $el.addEventListener('touchend', e => {
            let endX = e.changedTouches[0].clientX;
            let diff = startX - endX;
            if (diff > 50) next();     // swipe left
            if (diff < -50) prev();    // swipe right
        });
    "
>
    <div class="relative max-w-6xl w-full max-h-screen p-4">
        <template x-if="mediaItems.length > 0">
            <div>
                <template x-if="mediaItems[currentIndex].type === 'image'">
                    <img :src="mediaItems[currentIndex].url" class="w-full max-h-[90vh] object-contain rounded shadow-lg" />
                </template>

                <template x-if="mediaItems[currentIndex].type === 'video'">
                    <video
                        controls
                        autoplay
                        class="w-full max-h-[90vh] object-contain rounded shadow-lg"
                    >
                        <source :src="mediaItems[currentIndex].url" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </template>
            </div>
        </template>

        <!-- Close -->
        <button @click="open = false" class="absolute top-4 right-4 text-white text-3xl font-bold">&times;</button>

        <!-- Prev -->
        <button
            @click="prev"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-4xl font-bold"
        >&larr;</button>

        <!-- Next -->
        <button
            @click="next"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-4xl font-bold"
        >&rarr;</button>
    </div>
</div>
