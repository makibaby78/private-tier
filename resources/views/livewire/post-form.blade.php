<div class="space-y-2 mb-4 bg-white p-6 dark:bg-gray-800 shadow-sm sm:rounded-lg">
    @if (session()->has('message'))
        <div class="bg-green-200 text-green-800 p-2 rounded">
            {{ session('message') }}
        </div>
    @endif

    <textarea
        wire:model.defer="body"
        wire:loading.attr="disabled"
        wire:target="save"
        placeholder="What's on your mind?"
        class="w-full p-2 border-transparent bg-gray-100 rounded"
    ></textarea>
    @error('body')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror

    <div 
        x-data="{
            file: null,
            isUploading: false,
            resetWhenLivewireClears() {
                $watch('$wire.media', value => {
                    if (!value) {
                        file = null;
                        $refs.uploadMedia.value = null;
                    }
                });
            }
        }"
        x-init="resetWhenLivewireClears()"
        class="relative"
    >
        <!-- Hidden File Input -->
        <input
            type="file"
            wire:model="media"
            class="hidden"
            id="uploadMedia"
            accept="image/*,video/*"
            x-ref="uploadMedia"
            @change="
                file = $event.target.files[0];
                isUploading = true;
            "
            x-bind:disabled="isUploading"
        >

        <!-- Upload Trigger -->
        <div class="flex gap-x-2 items-center">
            <label 
                for="uploadMedia" 
                class="cursor-pointer inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200"
                :class="{ 'opacity-50 cursor-not-allowed': isUploading }"
            >
                <!-- Upload Icon -->
                <svg 
                    xmlns="http://www.w3.org/2000/svg" 
                    class="w-6 h-6 text-gray-600" 
                    fill="none" 
                    viewBox="0 0 24 24" 
                    stroke="currentColor"
                    x-show="!isUploading"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>

                <!-- Spinner -->
                <svg 
                    class="animate-spin w-5 h-5 text-gray-600" 
                    fill="none" 
                    viewBox="0 0 24 24"
                    x-show="isUploading"
                >
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
            </label>

            <p class="text-gray-500" x-show="!isUploading">Photo / Video</p>
            <p class="text-gray-500 italic" x-show="isUploading">Uploading...</p>
        </div>

        <!-- File Name Preview -->
        <template x-if="file">
            <p class="mt-2 text-sm text-gray-500 truncate">
                <strong>Selected:</strong> <span x-text="file.name"></span>
            </p>
        </template>

        @error('media')
            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
        @enderror

        <!-- Reset upload state once Livewire finishes -->
        <div 
            wire:loading.remove 
            wire:target="media"
            x-init="$watch('$wire.media', value => isUploading = false)"
        ></div>
    </div>

    @php
        use Illuminate\Support\Str;
    @endphp

    @if ($media)
        <div>
            <p class="text-sm text-gray-500">Preview:</p>

            @if (Str::startsWith($media->getMimeType(), 'image/'))
                <img src="{{ $media->temporaryUrl() }}" class="w-32 h-32 object-cover rounded" alt="Image preview">
            @elseif (Str::startsWith($media->getMimeType(), 'video/'))
                <video class="w-32 h-32 rounded" controls>
                    <source src="{{ $media->temporaryUrl() }}">
                    Your browser does not support the video tag.
                </video>
            @endif
        </div>
    @endif

    <button 
        type="submit" 
        wire:click="save" 
        wire:loading.attr="disabled" 
        wire:target="save"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
    >
        <span wire:loading.remove>Post</span>

        <span wire:loading class="flex items-center gap-2">
            <svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10"
                    stroke="currentColor" stroke-width="4" fill="none"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                </path>
            </svg>
            Posting...
        </span>
    </button>

</div>
