<template>
    <div class="min-h-screen bg-[#1E293B] text-white p-8 font-sans">
        
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-bold">Create Task</h1>
            <Link :href="route('tasks.index')" class="bg-gray-600 hover:bg-gray-500 text-white px-4 py-2 rounded-lg font-medium transition">
                Back to Tasks
            </Link>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            
            <div class="flex-1">
                <div class="bg-white text-gray-800 rounded-xl p-8 shadow-lg">
                    <form @submit.prevent="submit">
                        
                        <div class="mb-6">
                            <label class="block text-xl font-bold mb-2">Task Title</label>
                            <input v-model="form.title" type="text" placeholder="e.g. Launch New Campaign" 
                                   class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 outline-none" required />
                            <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</div>
                        </div>

                        <div class="mb-6">
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                                <textarea v-model="form.description" rows="4" placeholder="Task description..."
                                          class="w-full bg-transparent border-none outline-none resize-none focus:ring-0"></textarea>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block font-bold mb-3">Priority</label>
                            <div class="flex gap-4">
                                <button type="button" @click="form.priority = 'low'" 
                                        :class="form.priority === 'low' ? 'bg-blue-100 text-blue-600 border-blue-300' : 'bg-gray-100 text-gray-500 border-transparent'"
                                        class="px-6 py-2 rounded-full border font-medium transition">
                                    Low
                                </button>
                                <button type="button" @click="form.priority = 'medium'" 
                                        :class="form.priority === 'medium' ? 'bg-orange-100 text-orange-600 border-orange-300' : 'bg-gray-100 text-gray-500 border-transparent'"
                                        class="px-6 py-2 rounded-full border font-medium transition">
                                    Medium
                                </button>
                                <button type="button" @click="form.priority = 'high'" 
                                        :class="form.priority === 'high' ? 'bg-red-100 text-red-600 border-red-300' : 'bg-gray-100 text-gray-500 border-transparent'"
                                        class="px-6 py-2 rounded-full border font-medium transition">
                                    High
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="block font-bold text-gray-600 text-sm mb-2">Due Date</label>
                                <input v-model="form.due_date" type="date" 
                                       class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none focus:border-blue-500" />
                            </div>
                            <div>
                                <label class="block font-bold text-gray-600 text-sm mb-2">Assign To</label>
                                <select v-model="form.assigned_to" required
                                        class="w-full px-4 py-2 rounded-lg border border-gray-200 outline-none focus:border-blue-500 bg-white">
                                    <option value="" disabled>Select User</option>
                                    <option v-for="user in users" :key="user.id" :value="user.id">
                                        {{ user.name }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-center mt-8">
                            <button type="submit" :disabled="form.processing"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-8 py-3 rounded-full font-bold shadow-lg transition disabled:opacity-50">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="w-full lg:w-80 flex flex-col gap-6">
                <div class="bg-white text-gray-800 rounded-xl p-6 shadow-lg">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center font-bold text-gray-500 uppercase">
                            {{ $page.props.auth.user.name.substring(0, 2) }}
                        </div>
                        <div>
                            <div class="font-bold text-lg">{{ $page.props.auth.user.name }}</div>
                            <div class="text-xs text-gray-400 uppercase">{{ $page.props.auth.user.role }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    users: Array
});

// Inertia Form helper automatically handles errors and loading states
const form = useForm({
    title: '',
    description: '',
    priority: 'medium',
    due_date: '',
    assigned_to: ''
});

const submit = () => {
    // This posts to the store method we already built!
    form.post(route('tasks.store'), {
        onSuccess: () => {
            // Optional: You could show a toast notification here
        }
    });
};
</script>
