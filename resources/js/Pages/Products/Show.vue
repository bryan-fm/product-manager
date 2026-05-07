<script setup>
defineProps({
    product: Object,
});
</script>

<template>
    <div>
        <h1>{{ product.name }}</h1>

        <p>{{ product.description }}</p>

        <p>Preço: {{ product.price }}</p>

        <p>Estoque: {{ product.stock }}</p>

        <a href="/products">Voltar</a>
    </div>
</template>
