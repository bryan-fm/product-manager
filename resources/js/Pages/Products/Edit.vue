<script setup>
import ProductForm from '@/Components/ProductForm.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    product: Object,
});

const form = useForm({
    name: props.product.name,
    description: props.product.description,
    price: props.product.price,
    stock: props.product.stock,
});

function submit() {
    form.put(`/products/${props.product.id}`);
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="p-6">
            <h1 class="mb-4 text-2xl">Editar Produto</h1>

            <ProductForm
                :form="form"
                :submit="submit"
                buttonText="Salvar Alterações"
                mode="edit"
            />
        </div>
    </AuthenticatedLayout>
</template>
