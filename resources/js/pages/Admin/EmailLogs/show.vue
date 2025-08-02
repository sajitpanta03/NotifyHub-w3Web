<template>
    <AppLayout>
        <div class="px-4 py-8 sm:px-6 lg:px-8">
            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-xl font-semibold text-gray-400">Email Logs</h1>
                    <p class="mt-2 text-sm text-gray-400">A list of all email logs in the system.</p>
                </div>
            </div>

            <div class="mt-8 flex flex-col">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="ring-opacity-5 overflow-hidden shadow ring-1 ring-black md:rounded-lg dark:ring-gray-700">
                            <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-800">
                                    <tr>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            Recipient
                                        </th>
                                        <th
                                            scope="col"
                                            class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-6 dark:text-gray-100"
                                        >
                                            Subject
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            Message
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            Status
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            Created At
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                                    <tr v-for="log in logs" :key="log.id">
                                        <td class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-6 dark:text-gray-100">
                                            {{ log.recipient.name }}
                                        </td>
                                        <td class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-6 dark:text-gray-100">
                                            {{ log.subject }}
                                        </td>
                                        <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                            {{ log.message }}
                                        </td>
                                        <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                            <span
                                                :class="{
                                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': log.status === 'success',
                                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': log.status === 'queued',
                                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': log.status === 'failed',
                                                    'bg-red-100 text-white dark:bg-indigo-900 dark:text-red-200': log.status === 'pending',
                                                }"
                                                class="inline-flex rounded-full px-2 text-xs leading-5 font-semibold"
                                            >
                                                {{ log.status }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-4 text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                            {{ formatDate(log.created_at) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps({
    logs: Object,
});

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString();
};
</script>
