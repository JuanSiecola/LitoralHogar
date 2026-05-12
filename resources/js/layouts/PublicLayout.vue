<script setup lang="ts">
import LandingNavbar from '@/components/LandingNavbar.vue';
import Footer from '@/components/AppFooter.vue';
</script>

<template>
    <div class="flex min-h-screen flex-col bg-background">
        <LandingNavbar />
        <main class="flex-1">
            <slot />
        </main>
        <Footer />
    </div>
</template>
