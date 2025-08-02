<template>
    <AppLayout>
        <div class="px-4 py-8 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-xl font-semibold text-gray-400">Users</h1>
                    <p class="mt-2 text-sm text-gray-400">A list of all registered users in the system.</p>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <Link
                        class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none"
                        :href="`/admin/users/create`"
                        >Add User</Link
                    >
                </div>
            </div>

            <div class="mt-8 flex flex-col">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="ring-opacity-5 overflow-hidden shadow ring-1 ring-black md:rounded-lg dark:ring-gray-700">
                            <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-6 dark:text-gray-100"
                                        >
                                            Name
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            Email
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            Joined Date
                                        </th>
                                        <th scope="col" class="relative py-3.5 pr-4 pl-3 sm:pr-6">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                                    <tr v-for="user in users.data" :key="user.id">
                                        <td class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-6 dark:text-gray-100">
                                            {{ user.name }}
                                        </td>
                                        <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                            {{ user.email }}
                                        </td>
                                        <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                            {{ formatDate(user.created_at) }}
                                        </td>
                                        <td class="relative py-4 pr-4 pl-3 text-right text-sm font-medium whitespace-nowrap sm:pr-6">
                                            <Link
                                                :href="route('admin.emails.create', { id: user.id })"
                                                class="mr-3 text-orange-500 hover:text-indigo-900 dark:hover:text-indigo-300"
                                            >
                                                Send Mail
                                            </Link>
                                            <Link
                                                :href="`/admin/users/${user.id}`"
                                                class="mr-3 text-indigo-600 hover:text-indigo-900 dark:hover:text-indigo-300"
                                                >View</Link
                                            >
                                            <Link
                                                :href="`/admin/users/${user.id}/edit`"
                                                class="mr-3 text-white hover:text-indigo-900 dark:hover:text-indigo-300"
                                            >
                                                Edit
                                            </Link>
                                            <button
                                                @click="openDeleteModal(user)"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <DeleteModal :show="showDeleteModal" :url="deleteUrl" @close="closeDeleteModal" @deleted="handleUserDeleted" />
    </AppLayout>
</template>

<script setup>
import DeleteModal from '@/components/DeleteModal.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useToast } from 'vue-toast-notification';

const toast = useToast();

const props = defineProps({
    users: Object,
});

const showDeleteModal = ref(false);
const deleteUrl = ref('');
const currentUserId = ref(null);

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
};

const openDeleteModal = (user) => {
    currentUserId.value = user.id;
    deleteUrl.value = `/admin/users/${user.id}`;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
};
</script>
