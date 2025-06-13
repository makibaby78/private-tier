<form method="POST" action="{{ route('settings.upload-photo') }}" enctype="multipart/form-data">
    @csrf
    <div>
        <label for="photo">Upload Profile Photo (Cloudinary)</label>
        <input type="file" name="photo" id="photo">
    </div>
    <button type="submit">Upload</button>
</form>

@if (auth()->user()->profile_photo_url)
    <img src="{{ auth()->user()->profile_photo_url }}" alt="Profile Photo" width="100">
@endif