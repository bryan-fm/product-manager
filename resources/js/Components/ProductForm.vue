<script setup>
defineProps({
  form: Object,
  submit: Function,
  buttonText: String
});
</script>

<template>
  <form @submit.prevent="submit" class="space-y-4">
    <div>
      <input
        v-model="form.name"
        placeholder="Nome"
        class="border p-2 w-full"
      />
      <div class="text-red-500 text-sm">
        {{ form.errors.name }}
      </div>
    </div>

    <div>
      <textarea
        v-model="form.description"
        placeholder="Descrição"
        class="border p-2 w-full"
      />
    </div>

    <div>
      <input
        v-model="form.price"
        type="number"
        step="0.01"
        placeholder="Preço"
        class="border p-2 w-full"
      />

      <div class="text-red-500 text-sm">
        {{ form.errors.price }}
      </div>
    </div>

    <div>
      <input
        v-model="form.stock"
        type="number"
        placeholder="Estoque"
        class="border p-2 w-full"
      />

      <div class="text-red-500 text-sm">
        {{ form.errors.stock }}
      </div>
    </div>

    <button
      type="submit"
      class="bg-blue-500 text-white px-4 py-2 rounded"
    >
      {{ buttonText }}
    </button>
  </form>
</template>