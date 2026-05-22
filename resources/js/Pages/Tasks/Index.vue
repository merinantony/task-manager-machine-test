<template>
    <div class="min-h-screen bg-[#1E293B] text-white p-8 font-sans">
        
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold">Task List</h1>
            <Link v-if="$page.props.auth.user.role === 'admin'" :href="route('tasks.create')" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium shadow-lg transition">
                + New Task
            </Link>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            
            <div class="flex-1">
                
                <div class="flex gap-4 mb-6">
                    <input type="text" placeholder="Search Filter Task" class="px-4 py-2 rounded-lg text-gray-800 w-64 focus:ring-2 focus:ring-blue-500 outline-none" />
                    
                    <!-- Status Filter Dropdown -->
                    <div class="relative">
                        <button 
                            @click="toggleFilterDropdown('status')"
                            class="px-4 py-2 rounded-lg text-gray-800 outline-none border border-gray-200 cursor-pointer hover:border-blue-500 transition flex items-center gap-2 bg-white"
                        >
                            <span>{{ filters.status ? (filters.status === 'in_progress' ? 'In Progress' : filters.status.charAt(0).toUpperCase() + filters.status.slice(1)) : 'All Statuses' }}</span>
                            <svg class="w-3 h-3 text-gray-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                        <div 
                            v-if="openFilters.status"
                            class="absolute top-full left-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 min-w-max"
                        >
                            <button 
                                @click="filters.status = ''; toggleFilterDropdown('status')"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition"
                            >
                                All Statuses
                            </button>
                            <button 
                                @click="filters.status = 'pending'; toggleFilterDropdown('status')"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition border-t border-gray-100 flex items-center gap-2"
                            >
                                <span class="w-2 h-2 rounded-full" style="background-color: #3b82f6;"></span>
                                Pending
                            </button>
                            <button 
                                @click="filters.status = 'in_progress'; toggleFilterDropdown('status')"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 hover:text-yellow-600 transition border-t border-gray-100 flex items-center gap-2"
                            >
                                <span class="w-2 h-2 rounded-full" style="background-color: #eab308;"></span>
                                In Progress
                            </button>
                            <button 
                                @click="filters.status = 'completed'; toggleFilterDropdown('status')"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 transition border-t border-gray-100 flex items-center gap-2"
                            >
                                <span class="w-2 h-2 rounded-full" style="background-color: #22c55e;"></span>
                                Completed
                            </button>
                        </div>
                    </div>
                    
                    <!-- Priority Filter Dropdown -->
                    <div class="relative">
                        <button 
                            @click="toggleFilterDropdown('priority')"
                            class="px-4 py-2 rounded-lg text-gray-800 outline-none border border-gray-200 cursor-pointer hover:border-blue-500 transition flex items-center gap-2 bg-white"
                        >
                            <span>{{ filters.priority ? (filters.priority.charAt(0).toUpperCase() + filters.priority.slice(1)) : 'All Priorities' }}</span>
                            <svg class="w-3 h-3 text-gray-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </button>
                        <div 
                            v-if="openFilters.priority"
                            class="absolute top-full left-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 min-w-max"
                        >
                            <button 
                                @click="filters.priority = ''; toggleFilterDropdown('priority')"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition"
                            >
                                All Priorities
                            </button>
                            <button 
                                @click="filters.priority = 'high'; toggleFilterDropdown('priority')"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition border-t border-gray-100 flex items-center gap-2"
                            >
                                <span class="w-2 h-2 rounded-full" style="background-color: #ef4444;"></span>
                                High
                            </button>
                            <button 
                                @click="filters.priority = 'medium'; toggleFilterDropdown('priority')"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition border-t border-gray-100 flex items-center gap-2"
                            >
                                <span class="w-2 h-2 rounded-full" style="background-color: #f97316;"></span>
                                Medium
                            </button>
                            <button 
                                @click="filters.priority = 'low'; toggleFilterDropdown('priority')"
                                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition border-t border-gray-100 flex items-center gap-2"
                            >
                                <span class="w-2 h-2 rounded-full" style="background-color: #3b82f6;"></span>
                                Low
                            </button>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div v-for="task in tasks.data" :key="task.id" class="bg-white text-gray-800 rounded-xl p-6 shadow-lg relative">
                        
                        <div class="flex justify-between items-start mb-4">
                            <div class="relative">
                                <button 
                                    @click="toggleDropdown(task.id)"
                                    class="flex items-center gap-1 text-sm font-bold text-gray-600 hover:text-gray-800 transition"
                                >
                                    <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                                    <span class="uppercase whitespace-nowrap">{{ task.status.replace('_', ' ') }}</span>
                                    <svg class="w-3 h-3 text-gray-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                </button>
                                <div 
                                    v-if="openDropdowns[task.id]"
                                    class="absolute top-full left-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 min-w-max"
                                >
                                    <button 
                                        @click="updateStatus(task.id, 'pending'); toggleDropdown(task.id)"
                                        class="w-full text-left px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition uppercase"
                                    >
                                        PENDING
                                    </button>
                                    <button 
                                        @click="updateStatus(task.id, 'in_progress'); toggleDropdown(task.id)"
                                        class="w-full text-left px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-yellow-50 hover:text-yellow-600 transition uppercase border-t border-gray-100"
                                    >
                                        IN PROGRESS
                                    </button>
                                    <button 
                                        @click="updateStatus(task.id, 'completed'); toggleDropdown(task.id)"
                                        class="w-full text-left px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-green-50 hover:text-green-600 transition uppercase border-t border-gray-100"
                                    >
                                        COMPLETED
                                    </button>
                                </div>
                            </div>
                            <div class="relative">
                                <button 
                                    @click="toggleActionMenu(task.id)"
                                    class="text-gray-400 hover:text-gray-700 transition p-1"
                                >
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-0.9 2-2s-0.9-2-2-2-2 0.9-2 2 0.9 2 2 2zm0 2c-1.1 0-2 0.9-2 2s0.9 2 2 2 2-0.9 2-2-0.9-2-2-2zm0 6c-1.1 0-2 0.9-2 2s0.9 2 2 2 2-0.9 2-2-0.9-2-2-2z"></path></svg>
                                </button>
                                <div 
                                    v-if="openActionMenus[task.id]"
                                    class="absolute top-full right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg z-10 min-w-max"
                                >
                                    <Link 
                                        :href="route('tasks.edit', task.id)"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition flex items-center gap-2 block"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        Edit
                                    </Link>
                                    <Link 
                                        :href="route('tasks.show', task.id)"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 hover:text-purple-600 transition flex items-center gap-2 block border-t border-gray-100"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        View
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <h2 class="text-xl font-bold mb-2">{{ task.title }}</h2>
                        
                        <div class="flex gap-2 mb-4">
                            <span class="px-2 py-1 text-xs font-bold bg-gray-200 rounded-md text-gray-600">Status</span>
                            <span class="px-2 py-1 text-xs font-bold rounded-md" 
                                  :class="task.priority === 'high' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600'">
                                Priority: {{ task.priority }}
                            </span>
                        </div>

                        <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ task.description || 'No description provided.' }}</p>

                        <div class="flex justify-between items-end mt-4 pt-4 border-t border-gray-100">
                            <div class="text-xs text-gray-400">
                                <p>Assigned to: {{ task.user.name }}</p>
                                <p>Due: {{ task.due_date || 'N/A' }}</p>
                                <p class="text-blue-500 font-semibold mt-1">Due Priority: {{ task.priority }}</p>
                            </div>
                            <div class="flex gap-2">
                                <Link :href="route('tasks.edit', task.id)" class="px-4 py-1 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">Edit</Link>
                                <Link :href="route('tasks.show', task.id)" class="px-4 py-1 text-sm bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition">View</Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full lg:w-80 flex flex-col gap-6">
                <div class="bg-white text-gray-800 rounded-xl p-6 shadow-lg">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-full overflow-hidden shadow-sm">
                            <img :src="$page.props.auth.user.avatar_url" alt="Profile" class="w-full h-full object-cover" />
                        </div>
                        <div>
                            <div class="font-bold text-lg">{{ $page.props.auth.user.name }}</div>
                            <div class="text-xs text-gray-400 uppercase">{{ $page.props.auth.user.role }}</div>
                        </div>
                    </div>
                    
                    <ul class="flex flex-col gap-2 border-t pt-4 border-gray-100">
                        <li class="p-2 bg-blue-500 text-white rounded-lg cursor-pointer">Tasks</li>
                        <li v-if="$page.props.auth.user.role === 'admin'" class="p-2 hover:bg-gray-100 rounded-lg cursor-pointer text-gray-600">Users <span class="text-xs text-gray-400">(Admin only)</span></li>
                        <Link :href="route('logout')" method="post" as="button" class="w-full text-left p-2 hover:bg-gray-100 rounded-lg cursor-pointer text-red-500 mt-4 border-t border-gray-100 pt-4 font-medium transition">
                            Logout
                        </Link>
                    </ul>
                </div>

                <div class="bg-[#2A3B54] text-white rounded-xl p-6 shadow-lg">
                    <h3 class="font-bold mb-4">Monthly Task Completion</h3>
                    <div class="flex items-end justify-between h-32 mt-4 border-b border-gray-600 pb-2">
                        <div v-for="(stat, index) in props.monthlyStats" :key="index" class="w-8 bg-blue-500 rounded-t-md" :style="{ height: stat + '%' }"></div>
                    </div>
                    <div class="flex justify-between text-xs mt-2 text-gray-400">
                        <span v-for="(month, index) in props.monthLabels" :key="index">{{ month }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { router, Link } from '@inertiajs/vue3';

const props = defineProps({
    tasks: Object,
    monthlyStats: Array,
    monthLabels: Array
});

// Create a reactive object to hold the current filter values
const filters = ref({
    status: '',
    priority: ''
});

// Track which dropdowns are open
const openDropdowns = ref({});
const openFilters = ref({
    status: false,
    priority: false
});
const openActionMenus = ref({});

// Toggle dropdown visibility for task status
const toggleDropdown = (taskId) => {
    openDropdowns.value[taskId] = !openDropdowns.value[taskId];
};

// Toggle filter dropdown visibility
const toggleFilterDropdown = (filterType) => {
    openFilters.value[filterType] = !openFilters.value[filterType];
};

// Toggle action menu visibility
const toggleActionMenu = (taskId) => {
    openActionMenus.value[taskId] = !openActionMenus.value[taskId];
};

// Update task status via Inertia
const updateStatus = (taskId, newStatus) => {
    router.patch(route('tasks.updateStatus', taskId), { status: newStatus }, {
        preserveScroll: true, // Keeps the user from jumping to the top of the page
        preserveState: true   // Keeps the filters active
    });
};

// Watch the filters object. Whenever a dropdown changes, ask Inertia to reload the page with the new parameters.
watch(filters, (newFilters) => {
    router.get(route('tasks.index'), newFilters, {
        preserveState: true, // Keeps you from losing your place on the page
        replace: true        // Prevents filling up the browser "Back" button history
    });
}, { deep: true });
</script>