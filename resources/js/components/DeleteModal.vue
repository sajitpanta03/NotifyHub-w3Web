<template>
    <Transition name="fade">
        <div v-if="show" class="bg-opacity-50 fixed inset-0 z-50 flex items-center justify-center bg-black">
            <div class="w-full max-w-sm rounded-lg bg-white p-6 dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-medium text-gray-900 dark:text-white">
                    <slot name="title">Confirm Deletion</slot>
                </h3>
                <p class="mb-6 text-gray-600 dark:text-gray-300">
                    <slot name="message">Are you sure you want to delete this item? This action cannot be undone.</slot>
                </p>
                <div class="flex justify-end space-x-3">
                    <button
                        @click="closeModal"
                        class="rounded-md bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600"
                    >
                        Cancel
                    </button>
                    <button
                        @click="confirmDelete"
                        :disabled="processing"
                        class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-75"
                    >
                        <span v-if="processing">Deleting...</span>
                        <span v-else>Delete</span>
                    </button>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useToast } from 'vue-toast-notification';

const toast = useToast();

const props = defineProps({
    show: Boolean,
    url: {
        type: String,
        required: true,
    },
    preserveScroll: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits(['close', 'deleted']);

const processing = ref(false);

const confirmDelete = () => {
    processing.value = true;
    router.delete(props.url, {
        preserveScroll: props.preserveScroll,
        onSuccess: () => {
            emit('deleted');
            toast.success('User deleted successfully!', {
                position: 'top-right',
                duration: 5000,
            });
            closeModal();
        },
        onFinish: () => {
            processing.value = false;
        },
    });
};

const closeModal = () => {
    emit('close');
};
</script>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
