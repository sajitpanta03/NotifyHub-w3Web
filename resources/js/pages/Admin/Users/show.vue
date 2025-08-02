<template>
    <AppLayout>
        <div class="px-4 py-8 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">User Details</h1>
                    <p class="mt-2 text-sm text-gray-700 dark:text-gray-400">View detailed information about this user.</p>
                </div>
                <div class="mt-4 flex space-x-3 sm:mt-0 sm:ml-4">
                    <Link
                        :href="`/admin/users/${user.id}/edit`"
                        class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none"
                    >
                        Edit User
                    </Link>
                    <Link
                        href="/admin/users"
                        class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600"
                    >
                        Back to Users
                    </Link>
                </div>
            </div>

            <div class="mt-8 overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Profile Information</h3>
                </div>
                <div class="border-t border-gray-200 dark:border-gray-700">
                    <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                        <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-100">
                                {{ user.name }}
                            </dd>
                        </div>
                        <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Address</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-100">
                                {{ user.email }}
                            </dd>
                        </div>
                        <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Account Status</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-100">
                                <span
                                    :class="{
                                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': user.email_verified_at,
                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': !user.email_verified_at,
                                    }"
                                    class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                                >
                                    {{ user.email_verified_at ? 'Verified' : 'Pending Verification' }}
                                </span>
                            </dd>
                        </div>
                        <div class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Joined Date</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-100">
                                {{ formatDate(user.created_at) }}
                            </dd>
                        </div>
                        <div v-if="user.last_login_at" class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Login</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-100">
                                {{ formatDateTime(user.last_login_at) }}
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Additional Sections -->
            <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-2">
                <!-- Roles Section -->
                <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Roles</h3>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            <li v-if="user.roles && user.roles.length > 0" v-for="role in user.roles" :key="role.id" class="px-4 py-4">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">{{ role.name }}</p>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ role.description }}</span>
                                </div>
                            </li>
                            <li v-else class="px-4 py-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">No roles assigned</p>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Recent Activity Section -->
                <div class="overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Recent Activity</h3>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            <li
                                v-if="user.activities && user.activities.length > 0"
                                v-for="activity in user.activities.slice(0, 3)"
                                :key="activity.id"
                                class="px-4 py-4"
                            >
                                <div class="flex space-x-3">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ activity.description }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ formatDateTime(activity.created_at) }}</p>
                                    </div>
                                </div>
                            </li>
                            <li v-else class="px-4 py-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400">No recent activity</p>
                            </li>
                        </ul>
                        <div v-if="user.activities && user.activities.length > 3" class="px-4 py-4 text-center">
                            <Link
                                :href="`/admin/users/${user.id}/activity`"
                                class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
                            >
                                View all activity
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
});

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
};

const formatDateTime = (dateString) => {
    const options = {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    };
    return new Date(dateString).toLocaleDateString(undefined, options);
};
</script>
