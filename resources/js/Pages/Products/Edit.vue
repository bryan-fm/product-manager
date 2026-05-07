<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ProductForm from '@/Components/ProductForm.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
  product: Object
});

const form = useForm({
  name: props.product.name,
  description: props.product.description,
  price: props.product.price,
  stock: props.product.stock
});

function submit() {
  form.put(`/products/${props.product.id}`);
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="p-6">
      <h1 class="text-2xl mb-4">Editar Produto</h1>

      <ProductForm
        :form="form"
        :submit="submit"
        buttonText="Atualizar"
      />
    </div>
  </AuthenticatedLayout>
</template>