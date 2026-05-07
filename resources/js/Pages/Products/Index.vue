<script setup>
import Pagination from '@/Components/Pagination.vue';
import { Link } from '@inertiajs/vue3';
import { Pen, Trash } from 'lucide-vue-next';
defineProps({
  products: Object,
});

const headers = ['Nome', 'Descrição', 'Preço', 'Estoque', 'Ações'];
</script>

<template>
  <!-- Usar a URL como KEY é o "pulo do gato" para a tela atualizar ao mudar de página -->
  <div :key="$page.url">
    <div class="table-container"></div>
    <div class="p-6">
      <h1 class="mb-4 text-2xl font-bold">Produtos</h1>
      <p class="mb-8">Gerencie seus produtos</p>
      <table class="w-screen table-fixed">
        <thead>
          <tr>
            <!-- Loop through headers array to create <th> tags -->
            <th v-for="header in headers" :key="header">
              {{ header }}
            </th>
          </tr>
        </thead>
        <tbody>
          <!-- Loop through data items -->
          <tr v-for="(product, index) in products.data" :key="index">
            <td>{{ product.name }}</td>
            <td>{{ product.description }}</td>
            <td>{{ product.price }}</td>
            <td>{{ product.stock }}</td>
            <td>
              <Link
                :href="`/products/${product.id}/edit`"
                class="mt-2 inline-block text-sm text-blue-600 hover:underline"
              >
                <Pen :size="24" color="blue" />
              </Link>
              <Link
                :href="`/products/${product.id}`"
                class="mt-2 inline-block text-sm text-blue-600 hover:underline"
                method="delete"
              >
                <Trash :size="24" color="red" />
              </Link>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- <div
                v-for="product in products.data"
                :key="product.id"
                class="border p-4 mb-2 rounded shadow-sm"
            >
                <p class="font-semibold">{{ product.name }}</p>
                <p class="text-gray-600">R$ {{ product.price }}</p> -->

      <!-- <Link 
                    :href="`/products/${product.id}/edit`"
                    class="text-blue-600 hover:underline text-sm inline-block mt-2"
                >
                    Editar
                </Link> -->
      <!-- </div> -->

      <!-- Se não houver produtos -->
      <div v-if="products.data.length === 0" class="text-gray-500">
        Nenhum produto encontrado.
      </div>
      <!-- Passando os links para o componente filho -->
      <Pagination :links="products.links" />
    </div>
  </div>
</template>
