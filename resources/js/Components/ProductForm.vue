<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface ProductForm {
    name: string;
    description: string;
    price: number | string;
    stock: number | string;
    errors: {
        name?: string;
        description?: string;
        price?: string;
        stock?: string;
    };
    processing: boolean;
}

withDefaults(
    defineProps<{
        form: ProductForm;
        submit: () => void;
        buttonText?: string;
        mode?: 'create' | 'edit' | 'view';
    }>(),
    {
        buttonText: 'Salvar',
        mode: 'edit',
    },
);
</script>

<template>
    <form @submit.prevent="submit()" class="space-y-4">
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">
                Nome
            </label>
            <input
                v-model="form.name"
                :disabled="mode === 'view'"
                placeholder="Nome"
                class="w-full border p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:cursor-not-allowed disabled:bg-gray-100"
            />
            <div v-if="form.errors.name" class="text-sm text-red-500">
                {{ form.errors.name }}
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">
                Descrição
            </label>
            <textarea
                v-model="form.description"
                :disabled="mode === 'view'"
                placeholder="Descrição"
                class="w-full border p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:cursor-not-allowed disabled:bg-gray-100"
            />
            <div v-if="form.errors.description" class="text-sm text-red-500">
                {{ form.errors.description }}
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Preço
                </label>
                <input
                    v-model="form.price"
                    :disabled="mode === 'view'"
                    type="number"
                    step="0.01"
                    min="0.01"
                    placeholder="Preço"
                    class="w-full border p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:cursor-not-allowed disabled:bg-gray-100"
                />
                <div v-if="form.errors.price" class="text-sm text-red-500">
                    {{ form.errors.price }}
                </div>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700">
                    Estoque
                </label>
                <input
                    v-model="form.stock"
                    :disabled="mode === 'view'"
                    type="number"
                    min="0"
                    placeholder="Estoque"
                    class="w-full border p-2 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:cursor-not-allowed disabled:bg-gray-100"
                />
                <div v-if="form.errors.stock" class="text-sm text-red-500">
                    {{ form.errors.stock }}
                </div>
            </div>
        </div>

        <div class="flex gap-4">
            <button
                v-if="mode !== 'view'"
                type="submit"
                class="rounded bg-blue-500 px-4 py-2 text-white transition hover:bg-blue-600 disabled:opacity-50"
                :disabled="form.processing"
            >
                {{ form.processing ? 'Salvando...' : buttonText }}
            </button>

            <Link
                href="/products"
                class="rounded bg-gray-500 px-4 py-2 text-white transition hover:bg-gray-600"
            >
                Voltar
            </Link>
        </div>
    </form>
</template>
