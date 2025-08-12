<!-- Header -->
<header class="bg-white shadow-sm p-4 border-b border-gray-200 sticky top-0 z-10">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
        <div class="flex items-center space-x-4">
            <div class="relative">
                <button class="p-2 rounded-full hover:bg-gray-100 relative">
                    <i class="fas fa-bell"></i>
                    <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
            </div>
            <div class="dropdown relative">
                <button class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <span class="hidden md:inline">Admin</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
                <div class="dropdown-content mt-2 right-0 w-48 bg-white rounded-md shadow-lg py-1 z-10">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                    <div class="border-t border-gray-100"></div>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                </div>
            </div>
        </div>
    </div>
</header>
