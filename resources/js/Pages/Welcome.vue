<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    LayoutDashboard,
    LogIn,
    PackageSearch,
    UserPlus,
} from 'lucide-vue-next';

defineProps<{
    canLogin?: boolean;
    canRegister?: boolean;
    laravelVersion: string;
    phpVersion: string;
}>();
</script>

<template>
    <Head title="Product Manager" />

    <div
        class="flex min-h-screen flex-col bg-gray-50 text-gray-900 dark:bg-gray-950 dark:text-gray-100"
    >
        <!-- Background Decorativo Sutil -->
        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div
                class="absolute -left-[10%] -top-[10%] h-[40%] w-[40%] rounded-full bg-blue-500/5 blur-3xl"
            ></div>
            <div
                class="absolute -bottom-[10%] -right-[10%] h-[40%] w-[40%] rounded-full bg-indigo-500/5 blur-3xl"
            ></div>
        </div>

        <div class="relative flex flex-1 flex-col items-center">
            <div class="w-full max-w-7xl px-6">
                <!-- Header / Nav -->
                <header class="flex items-center justify-between py-10">
                    <div class="group flex items-center gap-2">
                        <div
                            class="rounded-lg bg-blue-600 p-2 shadow-lg shadow-blue-500/20 transition-transform group-hover:scale-110"
                        >
                            <PackageSearch class="h-8 w-8 text-white" />
                        </div>
                        <span
                            class="text-2xl font-black uppercase tracking-tight"
                        >
                            Product<span class="text-blue-600">Manager</span>
                        </span>
                    </div>

                    <nav v-if="canLogin" class="flex gap-4">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('products.index')"
                            class="flex items-center gap-2 rounded-xl bg-gray-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-gray-800 dark:bg-white dark:text-gray-900 dark:hover:bg-gray-200"
                        >
                            <LayoutDashboard :size="18" />
                            Acessar Painel
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:text-blue-600 dark:text-gray-300 dark:hover:text-white"
                            >
                                <LogIn :size="18" />
                                Entrar
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 transition hover:bg-blue-700"
                            >
                                <UserPlus :size="18" />
                                Criar Conta
                            </Link>
                        </template>
                    </nav>
                </header>

                <!-- Hero Section -->
                <main class="flex flex-col items-center py-20 text-center">
                    <h1
                        class="mb-6 text-5xl font-extrabold tracking-tight md:text-7xl"
                    >
                        Gerencie seus produtos <br />
                        <span
                            class="bg-gradient-to-r from-blue-600 to-indigo-500 bg-clip-text text-transparent"
                        >
                            com inteligência.
                        </span>
                    </h1>
                </main>
            </div>
        </div>
        <!-- Footer -->
        <footer
            class="mt-5 flex flex-col items-center justify-between gap-4 border-t border-gray-200 p-10 py-5 text-sm text-gray-500 md:flex-row dark:border-gray-800"
        >
            <p>© 2026 Bryan França. Todos os direitos reservados.</p>
            <div class="flex gap-6">
                <span>Laravel v{{ laravelVersion }}</span>
                <span>PHP v{{ phpVersion }}</span>
            </div>
        </footer>
    </div>
</template>
