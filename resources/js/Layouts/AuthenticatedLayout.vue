<script setup lang="ts">
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage<{
    auth: {
        user: {
            id: number;
            name: string;
            email: string;
        };
    };

    flash: {
        success?: string;
        error?: string;
    };
}>();

const successMessage = computed(() => page.props.flash?.success);

const errorMessage = computed(() => page.props.flash?.error);

const user = computed(
    () =>
        page.props.auth.user ?? {
            id: 0,
            name: '',
            email: '',
        },
);
</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Navbar -->
        <nav class="border-b bg-white shadow-sm">
            <div
                class="mx-auto flex h-16 max-w-7xl items-center justify-between px-6"
            >
                <!-- Logo / Title -->
                <div>
                    <h1 class="text-xl font-bold text-gray-800">
                        Product Manager
                    </h1>
                </div>
                <!-- User Dropdown -->
                <div>
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button
                                class="flex items-center gap-2 rounded-lg border px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
                            >
                                <div class="text-right">
                                    <p class="font-medium">
                                        {{ user.name }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        {{ user.email }}
                                    </p>
                                </div>

                                <svg
                                    class="h-4 w-4"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                            </button>
                        </template>

                        <template #content>
                            <DropdownLink :href="route('profile.edit')">
                                Perfil
                            </DropdownLink>

                            <DropdownLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Sair
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </div>
        </nav>

        <div
            v-if="successMessage"
            class="mb-4 rounded bg-green-100 p-4 text-green-700"
        >
            {{ successMessage }}
        </div>

        <div
            v-if="errorMessage"
            class="mb-4 rounded bg-red-100 p-4 text-red-700"
        >
            {{ errorMessage }}
        </div>

        <!-- Page Header -->
        <header v-if="$slots.header" class="border-b bg-white">
            <div class="mx-auto max-w-7xl px-6 py-5">
                <slot name="header" />
            </div>
        </header>

        <!-- Content -->
        <main class="mx-auto max-w-7xl p-6">
            <slot />
        </main>
    </div>
</template>
