<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  links: Array,
});
</script>

<template>
  <div v-if="links.length > 3">
    <div class="mt-4 flex gap-2">
      <template v-for="(link, index) in links" :key="index">
        <!-- Se não houver URL (Link desativado) -->
        <span
          v-if="link.url === null"
          class="cursor-not-allowed rounded border px-3 py-2 text-gray-400"
          v-html="link.label"
        />

        <!-- Se houver URL -->
        <Link
          v-else
          :href="link.url"
          class="rounded border px-3 py-2 transition-colors"
          :class="{
            'pointer-events-none cursor-default bg-blue-700 text-white':
              link.active,
            'hover:bg-gray-100': !link.active,
          }"
          v-html="link.label"
        />
      </template>
    </div>
  </div>
</template>
