<x-connect-layout>
    <div class="w-screen bg-gray-100 h-screen">
        <div class="flex h-full">
    
            <!-- Sidebar (Chats list) -->
            <div class="border-r border-gray-300 flex flex-col min-w- resize-x overflow-auto" style="min-width: 65px;">
                <!-- Search -->
                <div class="flex items-center gap-3 px-3 py-3 border-b border-gray-100">
                    <!-- hamburger -->
                    <button class="p-2 rounded-md hover:bg-gray-100">
                        <x-icons.hamburger />
                    </button>

                    <input type="text" placeholder="Search" class="w-full bg-gray-100 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200" />
                </div>
    
                <!-- Chats -->
                <div>
                    <!-- Beryl -->
                    <div class="flex items-center gap-3 p-3 hover:bg-gray-200 cursor-pointer">
                        <img src="https://ui-avatars.com/api/?name=B" class="w-10 h-10 rounded-full">
                        <div class="flex-1">
                            <div class="flex justify-between">
                                <span class="font-semibold">Beryl</span>
                                <span class="text-xs text-gray-500">Fri</span>
                            </div>
                            <p class="text-xs text-gray-600 truncate">
                                You set a self-destruct timer for all chats...
                            </p>
                        </div>
                    </div>
    
                    <!-- Samantha -->
                    <div class="flex items-center gap-3 p-3 hover:bg-gray-200 cursor-pointer">
                        <img src="https://ui-avatars.com/api/?name=Samantha" class="w-10 h-10 rounded-full">
                        <div class="flex-1">
                            <div class="flex justify-between">
                                <span class="font-semibold">Samantha</span>
                                <span class="text-xs text-gray-500">12/25/2024</span>
                            </div>
                            <p class="text-xs text-gray-600 truncate">
                                Hey there! Olivia from Upraw Media here...
                            </p>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Main Chat Area -->
            <div class="flex-1 bg-gradient-to-br from-green-200 to-green-300 flex items-center justify-center">
                <span class="text-gray-700 text-sm text-center">Select a chat to start messaging</span>
            </div>
    
        </div>
    </div>
</x-connect-layout>