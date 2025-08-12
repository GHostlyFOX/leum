@extends('layouts.app')

@section('content')
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Teams</p>
                            <p class="text-2xl font-bold">12</p>
                        </div>
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Active Players</p>
                            <p class="text-2xl font-bold">156</p>
                        </div>
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <i class="fas fa-user-friends"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Upcoming Matches</p>
                            <p class="text-2xl font-bold">7</p>
                        </div>
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                            <i class="fas fa-trophy"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Pending Actions</p>
                            <p class="text-2xl font-bold">3</p>
                        </div>
                        <div class="p-3 rounded-full bg-red-100 text-red-600">
                            <i class="fas fa-exclamation"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold">Upcoming Events</h2>
                            <div class="flex space-x-2">
                                <button class="px-3 py-1 text-sm bg-blue-600 text-white rounded-md">Add Event</button>
                                <button class="px-3 py-1 text-sm border border-gray-300 rounded-md">View All</button>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="p-3 border border-gray-200 rounded-md flex items-start space-x-3">
                                <div class="p-2 rounded-md bg-blue-100 text-blue-600">
                                    <i class="fas fa-running"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium">Team Practice</h3>
                                    <p class="text-sm text-gray-500">Today, 4:00 PM - 6:00 PM</p>
                                    <p class="text-sm">Central Park Field #2</p>
                                </div>
                                <button class="p-1 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>

                            <div class="p-3 border border-gray-200 rounded-md flex items-start space-x-3">
                                <div class="p-2 rounded-md bg-green-100 text-green-600">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium">Regional Tournament</h3>
                                    <p class="text-sm text-gray-500">Sat, Jun 10, 9:00 AM - 5:00 PM</p>
                                    <p class="text-sm">Regional Sports Complex</p>
                                </div>
                                <button class="p-1 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>

                            <div class="p-3 border border-gray-200 rounded-md flex items-start space-x-3">
                                <div class="p-2 rounded-md bg-purple-100 text-purple-600">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-medium">Parent Meeting</h3>
                                    <p class="text-sm text-gray-500">Mon, Jun 12, 6:00 PM - 7:30 PM</p>
                                    <p class="text-sm">Club Meeting Room</p>
                                </div>
                                <button class="p-1 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Team Performance -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold">Team Performance</h2>
                            <select class="border border-gray-300 rounded-md px-3 py-1 text-sm">
                                <option>Last 30 Days</option>
                                <option>Last 3 Months</option>
                                <option>Last 6 Months</option>
                                <option>This Year</option>
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div class="p-3 border border-gray-200 rounded-md">
                                <p class="text-sm text-gray-500 mb-1">Training Attendance</p>
                                <div class="flex items-end space-x-2">
                                    <p class="text-2xl font-bold">87%</p>
                                    <p class="text-sm text-green-500 flex items-center">
                                        <i class="fas fa-arrow-up mr-1"></i> 5%
                                    </p>
                                </div>
                            </div>
                            <div class="p-3 border border-gray-200 rounded-md">
                                <p class="text-sm text-gray-500 mb-1">Match Wins</p>
                                <div class="flex items-end space-x-2">
                                    <p class="text-2xl font-bold">65%</p>
                                    <p class="text-sm text-green-500 flex items-center">
                                        <i class="fas fa-arrow-up mr-1"></i> 12%
                                    </p>
                                </div>
                            </div>
                            <div class="p-3 border border-gray-200 rounded-md">
                                <p class="text-sm text-gray-500 mb-1">Goals Scored</p>
                                <div class="flex items-end space-x-2">
                                    <p class="text-2xl font-bold">42</p>
                                    <p class="text-sm text-red-500 flex items-center">
                                        <i class="fas fa-arrow-down mr-1"></i> 3%
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="h-64 bg-gray-50 rounded-md flex items-center justify-center">
                            <p class="text-gray-400">Performance chart will be displayed here</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div>
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 mb-6">
                        <h2 class="text-lg font-semibold mb-4">Recent Activity</h2>

                        <div class="space-y-4">
                            <div class="flex space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-user-plus text-blue-600"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm"><span class="font-medium">Michael Johnson</span> joined <span class="font-medium">U12 Boys Team</span></p>
                                    <p class="text-xs text-gray-500">2 hours ago</p>
                                </div>
                            </div>

                            <div class="flex space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <i class="fas fa-trophy text-green-600"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm"><span class="font-medium">U14 Girls Team</span> won against <span class="font-medium">Westside FC</span> (3-1)</p>
                                    <p class="text-xs text-gray-500">Yesterday</p>
                                </div>
                            </div>

                            <div class="flex space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center">
                                        <i class="fas fa-calendar-plus text-yellow-600"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm">New training session added for <span class="font-medium">U10 Mixed Team</span></p>
                                    <p class="text-xs text-gray-500">2 days ago</p>
                                </div>
                            </div>

                            <div class="flex space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                        <i class="fas fa-file-upload text-purple-600"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm"><span class="font-medium">Coach Smith</span> uploaded new training videos</p>
                                    <p class="text-xs text-gray-500">3 days ago</p>
                                </div>
                            </div>

                            <div class="flex space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                        <i class="fas fa-exclamation text-red-600"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm"><span class="font-medium">Sarah Williams</span> is injured until Jun 15</p>
                                    <p class="text-xs text-gray-500">4 days ago</p>
                                </div>
                            </div>
                        </div>

                        <button class="mt-4 w-full py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50">
                            View All Activity
                        </button>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                        <h2 class="text-lg font-semibold mb-4">Quick Actions</h2>

                        <div class="grid grid-cols-2 gap-3">
                            <button class="p-3 border border-gray-200 rounded-md hover:bg-gray-50 flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mb-2">
                                    <i class="fas fa-plus"></i>
                                </div>
                                <span class="text-sm">Add Player</span>
                            </button>

                            <button class="p-3 border border-gray-200 rounded-md hover:bg-gray-50 flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center mb-2">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <span class="text-sm">Schedule Training</span>
                            </button>

                            <button class="p-3 border border-gray-200 rounded-md hover:bg-gray-50 flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center mb-2">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <span class="text-sm">Add Tournament</span>
                            </button>

                            <button class="p-3 border border-gray-200 rounded-md hover:bg-gray-50 flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mb-2">
                                    <i class="fas fa-file-import"></i>
                                </div>
                                <span class="text-sm">Import Data</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teams Section -->
            <div class="mt-8">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold">Your Teams</h2>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>Add Team</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Team Card 1 -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="h-32 bg-gradient-to-r from-blue-500 to-blue-600 relative">
                            <div class="absolute top-3 right-3">
                                <button class="p-1 rounded-full bg-white bg-opacity-20 text-white hover:bg-opacity-30">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                            <div class="absolute bottom-3 left-3">
                                <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center shadow-md">
                                    <i class="fas fa-futbol text-blue-600 text-2xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 pt-12">
                            <h3 class="font-bold text-lg mb-1">U12 Boys Team</h3>
                            <p class="text-sm text-gray-500 mb-3">12 players • Coach: John Smith</p>
                            <div class="flex justify-between items-center">
                                <div class="flex -space-x-2">
                                    <img class="w-8 h-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/boys/1.jpg" alt="">
                                    <img class="w-8 h-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/boys/2.jpg" alt="">
                                    <img class="w-8 h-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/boys/3.jpg" alt="">
                                    <div class="w-8 h-8 rounded-full border-2 border-white bg-gray-100 flex items-center justify-center text-xs font-medium">+9</div>
                                </div>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Active</span>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 px-4 py-2 flex justify-between">
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                                <i class="fas fa-eye mr-1"></i> View
                            </a>
                            <a href="#" class="text-sm text-gray-600 hover:text-gray-800 flex items-center">
                                <i class="fas fa-calendar-alt mr-1"></i> Schedule
                            </a>
                            <a href="#" class="text-sm text-gray-600 hover:text-gray-800 flex items-center">
                                <i class="fas fa-chart-bar mr-1"></i> Stats
                            </a>
                        </div>
                    </div>

                    <!-- Team Card 2 -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="h-32 bg-gradient-to-r from-green-500 to-green-600 relative">
                            <div class="absolute top-3 right-3">
                                <button class="p-1 rounded-full bg-white bg-opacity-20 text-white hover:bg-opacity-30">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                            <div class="absolute bottom-3 left-3">
                                <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center shadow-md">
                                    <i class="fas fa-futbol text-green-600 text-2xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 pt-12">
                            <h3 class="font-bold text-lg mb-1">U14 Girls Team</h3>
                            <p class="text-sm text-gray-500 mb-3">15 players • Coach: Sarah Johnson</p>
                            <div class="flex justify-between items-center">
                                <div class="flex -space-x-2">
                                    <img class="w-8 h-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/girls/1.jpg" alt="">
                                    <img class="w-8 h-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/girls/2.jpg" alt="">
                                    <img class="w-8 h-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/girls/3.jpg" alt="">
                                    <div class="w-8 h-8 rounded-full border-2 border-white bg-gray-100 flex items-center justify-center text-xs font-medium">+12</div>
                                </div>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Active</span>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 px-4 py-2 flex justify-between">
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                                <i class="fas fa-eye mr-1"></i> View
                            </a>
                            <a href="#" class="text-sm text-gray-600 hover:text-gray-800 flex items-center">
                                <i class="fas fa-calendar-alt mr-1"></i> Schedule
                            </a>
                            <a href="#" class="text-sm text-gray-600 hover:text-gray-800 flex items-center">
                                <i class="fas fa-chart-bar mr-1"></i> Stats
                            </a>
                        </div>
                    </div>

                    <!-- Team Card 3 -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="h-32 bg-gradient-to-r from-purple-500 to-purple-600 relative">
                            <div class="absolute top-3 right-3">
                                <button class="p-1 rounded-full bg-white bg-opacity-20 text-white hover:bg-opacity-30">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                            <div class="absolute bottom-3 left-3">
                                <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center shadow-md">
                                    <i class="fas fa-futbol text-purple-600 text-2xl"></i>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 pt-12">
                            <h3 class="font-bold text-lg mb-1">U10 Mixed Team</h3>
                            <p class="text-sm text-gray-500 mb-3">10 players • Coach: Mike Brown</p>
                            <div class="flex justify-between items-center">
                                <div class="flex -space-x-2">
                                    <img class="w-8 h-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/boys/4.jpg" alt="">
                                    <img class="w-8 h-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/girls/4.jpg" alt="">
                                    <img class="w-8 h-8 rounded-full border-2 border-white" src="https://randomuser.me/api/portraits/boys/5.jpg" alt="">
                                    <div class="w-8 h-8 rounded-full border-2 border-white bg-gray-100 flex items-center justify-center text-xs font-medium">+7</div>
                                </div>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Inactive</span>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 px-4 py-2 flex justify-between">
                            <a href="#" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                                <i class="fas fa-eye mr-1"></i> View
                            </a>
                            <a href="#" class="text-sm text-gray-600 hover:text-gray-800 flex items-center">
                                <i class="fas fa-calendar-alt mr-1"></i> Schedule
                            </a>
                            <a href="#" class="text-sm text-gray-600 hover:text-gray-800 flex items-center">
                                <i class="fas fa-chart-bar mr-1"></i> Stats
                            </a>
                        </div>
                    </div>
                </div>
            </div>
@endsection
