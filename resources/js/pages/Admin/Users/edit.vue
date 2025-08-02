<template>
    <AppLayout>
        <div class="px-4 py-8 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Edit User</h1>
                    <p class="mt-2 text-sm text-gray-700 dark:text-gray-400">Update user information.</p>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <Link
                        href="/admin/users"
                        class="inline-flex items-center rounded-md border border-transparent bg-gray-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 focus:outline-none"
                    >
                        Back to Users
                    </Link>
                </div>
            </div>

            <div class="mt-8 overflow-hidden rounded-lg bg-white shadow dark:bg-gray-800">
                <form @submit.prevent="submitForm" class="p-6 sm:p-8">
                    <div class="space-y-8">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                            <div class="mt-1">
                                <input
                                    type="text"
                                    id="name"
                                    v-model="form.name"
                                    class="block w-full rounded-md border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                                    :class="{ 'border-red-500': form.errors.name }"
                                    placeholder="John Doe"
                                />
                                <p v-if="form.errors.name" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.name }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                            <div class="mt-1">
                                <input
                                    type="email"
                                    id="email"
                                    v-model="form.email"
                                    class="block w-full rounded-md border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400"
                                    :class="{ 'border-red-500': form.errors.email }"
                                    placeholder="user@example.com"
                                />
                                <p v-if="form.errors.email" class="mt-2 text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.email }}
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 pt-6">
                            <Link
                                href="/admin/users"
                                class="rounded-md border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600"
                            >
                                Cancel
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-70"
                            >
                                <svg
                                    v-if="form.processing"
                                    class="mr-2 -ml-1 h-4 w-4 animate-spin text-white"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    ></path>
                                </svg>
                                <span>{{ form.processing ? 'Saving...' : 'Save Changes' }}</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { useToast } from 'vue-toast-notification';

const props = defineProps({
    user: Object,
});

const toast = useToast();

const form = useForm({
    name: props.user.name,
    email: props.user.email,
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

const submitForm = () => {
    form.put(`/admin/users/${props.user.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('User edited successfully!', {
                position: 'top-right',
                duration: 5000,
            });
        },
        onError: () => {
            toast.success('User edited error!', {
                position: 'top-right',
                duration: 5000,
            });
        },
    });
};
</script>
