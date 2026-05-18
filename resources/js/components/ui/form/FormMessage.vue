<script setup lang="ts">
defineProps<{ message?: string }>()
</script>

<template>
    <p v-if="message" class="text-xs font-medium text-danger" role="alert">
        {{ message }}
    </p>
</template>
