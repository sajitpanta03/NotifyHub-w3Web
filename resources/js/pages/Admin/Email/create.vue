<template>
    <AppLayout>
        <Head title="Send Email" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <h1 class="mb-6 text-2xl font-semibold text-gray-900 dark:text-white">Send Email to {{ selectedUser.name }}</h1>

                    <form @submit.prevent="submitForm">
                        <input type="hidden" v-model="form.recipients" />

                        <div class="mb-4">
                            <label class="mb-2 block text-gray-700 dark:text-gray-300"> Recipient </label>
                            <div class="rounded bg-gray-100 p-3 dark:bg-gray-700">{{ selectedUser.name }} ({{ selectedUser.email }})</div>
                        </div>

                        <div class="mb-4">
                            <label class="mb-2 block text-gray-700 dark:text-gray-300"> Subject </label>
                            <input
                                v-model="form.subject"
                                type="text"
                                class="h-10 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required
                            />
                        </div>

                        <div class="mb-4">
                            <label class="mb-2 block text-gray-700 dark:text-gray-300"> Message </label>
                            <textarea
                                v-model="form.message"
                                rows="5"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                                required
                            ></textarea>
                        </div>

                        <div class="flex items-center justify-end">
                            <button
                                type="submit"
                                class="rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-none"
                                :disabled="form.processing"
                            >
                                <span v-if="form.processing">Sending...</span>
                                <span v-else>Send Email</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

const $toast = useToast();
const props = defineProps({
    selectedUser: Object,
    preselectedUserId: Number,
});

const form = useForm({
    recipients: [props.preselectedUserId],
    subject: '',
    message: '',
});

const submitForm = () => {
    form.post(route('admin.emails.store'), {
        onSuccess: () => {
            $toast.success('Email sent successfully!', {
                position: 'top-right',
                duration: 5000,
            });
        },
        onError: (errors) => {
            $toast.error('Failed to send email: ' + Object.values(errors).join(' '), {
                position: 'top-right',
                duration: 5000,
            });
        },
        onFinish: () => {
            form.reset('message');
        },
    });
};
</script>
