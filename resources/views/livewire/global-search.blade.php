<div class="w-full max-w-sm min-w-[200px] relative">

    <div class="relative">
        <form method="GET" action="/search" class="relative w-full max-w-sm">
            <input
                type="text"
                name="q"
                wire:model.live="q"
                placeholder="Search People or Posts"
                class="w-full bg-transparent placeholder:text-slate-400 text-slate-700 dark:text-white text-sm border border-slate-200 rounded-md pl-3 pr-12 py-2 transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-300 shadow-sm focus:shadow"
            />
            <button
                class="absolute top-1 right-1 flex items-center rounded bg-slate-800 py-1 px-2.5 border border-transparent text-center text-sm text-white transition-all shadow-sm hover:shadow focus:bg-slate-700 focus:shadow-none active:bg-slate-700 hover:bg-slate-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                type="submit"
            >
                <x-icons.magnifying-glass />
            </button> 
        </form>
    </div>

    @if(strlen($q) > 1)
        <div class="absolute z-10 mt-2 w-full bg-white border rounded shadow max-h-60 overflow-y-auto">
            @if($users->count())
                @foreach ($users as $user)
                    <a href="{{ route('profile.index', ['username' => $user->username]) }}">
                        <div class="px-4 py-2 hover:bg-gray-100 flex gap-x-2">
                            <div>
                                <x-profile-photo 
                                    :path="$user->profile_public_id" 
                                    :alt="$user->name" 
                                    class="rounded-full object-cover w-10 h-10" 
                                    width="30" 
                                    height="30" 
                                />
                            </div>
                            <div>
                                <strong class="text-base">{{ $user->name }}</strong>
                                <p class="text-sm text-gray-500">Person</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif

            @if($posts->count())
                @foreach ($posts as $post)
                    <div class="px-4 py-2 hover:bg-gray-100">
                        <strong>{{ $post->title }}</strong>
                        <p class="text-base">{{ Str::limit($post->body, 60) }}</p>
                        <p class="text-sm text-gray-500">Post</p>
                    </div>
                @endforeach
            @endif

            @if($users->isEmpty() && $posts->isEmpty())
                <div class="px-4 py-2 text-gray-500">No results found.</div>
            @endif
        </div>
    @endif

</div>
