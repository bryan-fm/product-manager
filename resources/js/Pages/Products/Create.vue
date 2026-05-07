<script setup>
import ProductForm from '@/Components/ProductForm.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
  name: '',
  description: '',
  price: '',
  stock: '',
});

function submit() {
  form.post('/products');
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="p-6">
      <h1 class="mb-4 text-2xl">Novo Produto</h1>

      <ProductForm :form="form" :submit="submit" buttonText="Salvar" />
    </div>
  </AuthenticatedLayout>
</template>
