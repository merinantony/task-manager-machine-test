<template>
    <div class="min-h-screen bg-[#1E293B] text-white p-8 font-sans">
        
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold">Task Detail + AI Summary</h1>
            <Link :href="route('tasks.index')" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium shadow-lg transition">
                + New Task
            </Link>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            
            <div class="flex-1">
                <div class="bg-white text-gray-800 rounded-xl p-8 shadow-lg">
                    
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 class="text-3xl font-bold mb-4">{{ task.title }}</h2>
                            <div class="flex gap-4">
                                <span class="px-4 py-1 bg-gray-100 rounded-full text-sm font-semibold text-gray-600 border border-gray-200">
                                    Status <span class="font-normal">{{ task.status.replace('_', ' ') }}</span>
                                </span>
                                <span class="px-4 py-1 rounded-full text-sm font-semibold border"
                                      :class="task.priority === 'high' ? 'bg-red-50 text-red-600 border-red-200' : (task.priority === 'medium' ? 'bg-orange-50 text-orange-600 border-orange-200' : 'bg-blue-50 text-blue-600 border-blue-200')">
                                    Priority <span class="font-normal">{{ task.priority }}</span>
                                </span>
                            </div>
                        </div>
                        <span class="text-gray-400 cursor-pointer text-2xl">•••</span>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-100 mb-8">
                        <h3 class="font-bold text-lg mb-4">Description</h3>
                        <p class="text-gray-600 mb-4"><span class="font-bold text-gray-800">Assigned to:</span> {{ task.user?.name || 'Unassigned' }}</p>
                        
                        <div class="flex items-center gap-2 mb-6">
                            <div class="bg-white px-4 py-2 border border-gray-200 rounded-md w-full max-w-md text-gray-500 flex justify-between">
                                <span>Due Date: {{ task.due_date || 'Not set' }}</span>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed mb-8">
                            {{ task.description || 'No description provided.' }}
                        </p>

                        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                            <h4 class="font-bold text-lg mb-4">AI-Generated Summary</h4>
                            <p class="text-gray-600 leading-relaxed mb-6">
                                {{ task.ai_summary || 'The AI has not generated a summary for this task yet. Click the refresh button in the sidebar to generate one.' }}
                            </p>
                            
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 font-medium">
                                <span class="text-gray-800">AI Summary:</span> <span class="text-gray-500 font-normal">Comprehensive campaign analysis...</span> 
                                <span class="ml-4 text-gray-800">Priority:</span> <span class="font-normal text-gray-500">{{ task.ai_priority ? task.ai_priority : 'Pending' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center">
                        <Link :href="route('tasks.edit', task.id)" class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-full font-bold shadow-lg transition">
                            Edit Task
                        </Link>
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
                            <div class="text-xs text-gray-400 uppercase" v-if="$page.props.auth.user.role === 'admin'">ADMIN</div>
                        </div>
                    </div>
                    
                    <ul class="flex flex-col gap-2 border-t pt-4 border-gray-100">
                        <Link :href="route('tasks.index')" class="p-2 bg-blue-500 text-white rounded-lg cursor-pointer block">Tasks</Link>
                        <li v-if="$page.props.auth.user.role === 'admin'" class="p-2 hover:bg-gray-100 rounded-lg cursor-pointer text-gray-600 flex justify-between">
                            Users <span class="text-xs text-gray-400">Only visible to Admin</span>
                        </li>
                        <Link :href="route('logout')" method="post" as="button" class="w-full text-left p-2 hover:bg-gray-100 rounded-lg cursor-pointer text-red-500 mt-4 border-t border-gray-100 pt-4 font-medium transition">
                            Logout
                        </Link>
                    </ul>

                    <div class="flex justify-between items-center mt-6 pt-6 border-t border-gray-100">
                        <div class="flex flex-col items-center">
                            <div class="w-14 h-14 rounded-full border-4 border-blue-500 flex items-center justify-center text-gray-800 font-bold text-lg">150</div>
                            <span class="text-[10px] text-gray-400 mt-1 text-center leading-tight">Total Tasks</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-14 h-14 rounded-full border-4 border-gray-300 flex items-center justify-center text-gray-800 font-bold text-lg">90</div>
                            <span class="text-[10px] text-gray-400 mt-1 text-center leading-tight">Completed</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-14 h-14 rounded-full border-4 border-gray-300 flex items-center justify-center text-gray-800 font-bold text-lg">60</div>
                            <span class="text-[10px] text-gray-400 mt-1 text-center leading-tight">Pending</span>
                        </div>
                    </div>
                    <div class="text-center text-xs text-gray-500 mt-4">Monthly Task Completion</div>
                </div>

                <Link :href="route('tasks.getAiSummary', task.id)" 
                      class="w-full bg-white text-blue-500 hover:bg-blue-50 py-3 rounded-lg font-bold flex justify-center items-center gap-2 shadow-lg transition">
                    Refresh AI Summary 
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
                </Link>

                <div class="bg-[#2A3B54] text-white rounded-xl p-6 shadow-lg">
                    <h3 class="font-bold mb-6">Monthly Task Completion</h3>
                    <div class="flex items-end justify-between h-32 border-b border-gray-600 pb-2 relative">
                        <div class="absolute left-0 top-0 h-full flex flex-col justify-between text-xs text-gray-400 -ml-2">
                            <span>180</span><span>90</span><span>0</span>
                        </div>
                        <div class="w-8 bg-blue-400 rounded-t-sm ml-6" style="height: 45%;"></div>
                        <div class="w-8 bg-blue-500 rounded-t-sm" style="height: 85%;"></div>
                        <div class="w-8 bg-blue-400 rounded-t-sm" style="height: 35%;"></div>
                        <div class="w-8 bg-blue-500 rounded-t-sm" style="height: 75%;"></div>
                    </div>
                    <div class="flex justify-between text-xs mt-3 text-gray-400 pl-6">
                        <span>Jan</span><span>Feb</span><span>Mar</span><span>Apr</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    task: Object
});
</script>
