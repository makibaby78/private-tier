

<form method="POST" action="{{ route('settings.upload-photo') }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-2">
        <input type="file" name="photo" id="photo"
        class="w-full text-slate-500 font-medium text-sm bg-gray-100 file:cursor-pointer cursor-pointer file:border-0 file:py-2 file:px-4 file:mr-4 file:bg-gray-800 file:hover:bg-gray-700 file:text-white rounded" />
    </div>

    <x-profile-photo 
        :path="$user->profile_photo_path" 
        :alt="$user->name" 
        class="rounded object-cover w-32 h-32 mb-2" 
        width="50" 
        height="50" 
    />
    
    <x-primary-button>{{ __('Save') }}</x-primary-button>
</form>