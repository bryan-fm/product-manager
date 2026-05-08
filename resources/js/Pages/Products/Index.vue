<script setup>
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import Pagination from '@/Components/Pagination.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Eye, Pen, Trash } from 'lucide-vue-next';
import { ref } from 'vue'; // Certifique-se de que está entre chaves {}

const props = defineProps({
    products: Object,
});

// Configuração estática do Header
const headers = [
    { label: 'Nome', width: 'w-64' },
    { label: 'Descrição', width: 'flex-1' },
    { label: 'Preço', width: 'w-32' },
    { label: 'Estoque', width: 'w-32' },
    { label: 'Ações', width: 'w-48' },
];

// Estado do Modal de Exclusão
const showModal = ref(false);
const idParaExcluir = ref(null);
const nomeParaExcluir = ref('');

// Função para abrir o modal de confirmação
const handleOpenModal = (product) => {
    idParaExcluir.value = product.id;
    nomeParaExcluir.value = product.name;
    showModal.value = true;
};

// Função que executa a exclusão via Inertia
const confirmarExclusao = () => {
    router.delete(`/products/${idParaExcluir.value}`, {
        onSuccess: () => {
            showModal.value = false;
            idParaExcluir.value = null;
        },
    });
};
</script>

<template>
    <Head title="Produtos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Gerenciar Produtos
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg"
                >
                    <!-- Topo: Título e Botão Criar -->
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                Catálogo de Itens
                            </h1>
                            <p class="text-sm text-gray-500">
                                Listagem geral de produtos e estoque
                            </p>
                        </div>
                        <Link
                            href="/products/create"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700"
                        >
                            + Cadastrar Produto
                        </Link>
                    </div>

                    <!-- Tabela Estilizada -->
                    <div
                        class="overflow-hidden rounded-lg border border-gray-200"
                    >
                        <!-- Header -->
                        <div
                            class="flex border-b bg-gray-50 font-bold text-gray-700"
                        >
                            <div
                                v-for="column in headers"
                                :key="column.label"
                                :class="[
                                    column.width,
                                    'p-4 text-sm uppercase tracking-wider',
                                ]"
                            >
                                {{ column.label }}
                            </div>
                        </div>

                        <!-- Corpo da Tabela (Linhas) -->
                        <div v-if="products.data.length > 0">
                            <div
                                v-for="product in products.data"
                                :key="product.id"
                                class="flex items-center border-b bg-white transition last:border-0 hover:bg-gray-50"
                            >
                                <div
                                    class="w-64 p-4 text-sm font-semibold text-gray-900"
                                >
                                    {{ product.name }}
                                </div>
                                <div
                                    class="flex-1 truncate p-4 text-sm text-gray-600"
                                >
                                    {{ product.description || 'Sem descrição' }}
                                </div>
                                <div class="w-32 p-4 text-sm text-gray-700">
                                    R$
                                    {{ parseFloat(product.price).toFixed(2) }}
                                </div>
                                <div class="w-32 p-4 text-center text-sm">
                                    <span
                                        :class="
                                            product.stock > 0
                                                ? 'text-green-600'
                                                : 'text-red-600'
                                        "
                                    >
                                        {{ product.stock }} un
                                    </span>
                                </div>

                                <!-- Ações -->
                                <div
                                    class="flex w-48 items-center justify-center gap-5 p-4"
                                >
                                    <Link
                                        :href="`/products/${product.id}`"
                                        title="Visualizar"
                                    >
                                        <Eye
                                            :size="20"
                                            class="text-gray-400 hover:text-gray-600"
                                        />
                                    </Link>

                                    <Link
                                        :href="`/products/${product.id}/edit`"
                                        title="Editar"
                                    >
                                        <Pen
                                            :size="19"
                                            class="text-blue-500 hover:text-blue-700"
                                        />
                                    </Link>

                                    <button
                                        type="button"
                                        @click="handleOpenModal(product)"
                                        title="Excluir"
                                    >
                                        <Trash
                                            :size="19"
                                            class="text-red-500 hover:text-red-700"
                                        />
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div v-else class="py-12 text-center">
                            <p class="text-gray-500">
                                Nenhum produto encontrado no sistema.
                            </p>
                        </div>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-6">
                        <Pagination :links="products.links" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Confirmação Reutilizável -->
        <ConfirmationModal
            :show="showModal"
            title="Excluir Produto"
            variant="danger"
            confirm-text="Sim, Excluir"
            @close="showModal = false"
            @confirm="confirmarExclusao"
        >
            Tem certeza que deseja apagar o produto
            <strong class="text-gray-900">{{ nomeParaExcluir }}</strong
            >? Esta operação não poderá ser desfeita.
        </ConfirmationModal>
    </AuthenticatedLayout>
</template>
