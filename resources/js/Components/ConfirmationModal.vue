<script setup>
defineProps({
    show: Boolean,
    title: String,
    confirmText: { type: String, default: 'Confirmar' },
    cancelText: { type: String, default: 'Cancelar' },
    variant: { type: String, default: 'danger' }, // danger, info, success
});

defineEmits(['close', 'confirm']);
</script>

<template>
    <div
        v-if="show"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
    >
        <!-- Overlay -->
        <div
            class="fixed inset-0 bg-black/50 transition-opacity"
            @click="$emit('close')"
        ></div>

        <!-- Modal Content -->
        <div
            class="relative w-full max-w-md overflow-hidden rounded-lg bg-white p-6 shadow-xl"
        >
            <h3 class="mb-2 text-xl font-bold text-gray-900">{{ title }}</h3>

            <div class="mb-6 text-gray-600">
                <!-- Slot para o corpo do texto -->
                <slot />
            </div>

            <div class="flex justify-end gap-3">
                <button
                    @click="$emit('close')"
                    class="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200"
                >
                    {{ cancelText }}
                </button>

                <button
                    @click="$emit('confirm')"
                    :class="[
                        'rounded-lg px-4 py-2 text-sm font-medium text-white transition-colors',
                        variant === 'danger'
                            ? 'bg-red-600 hover:bg-red-700'
                            : 'bg-blue-600 hover:bg-blue-700',
                    ]"
                >
                    {{ confirmText }}
                </button>
            </div>
        </div>
    </div>
</template>
