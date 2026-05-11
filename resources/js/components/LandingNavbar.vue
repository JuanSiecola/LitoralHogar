<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <header class="sticky top-0 z-40 border-b border-border/60 bg-background/95 backdrop-blur">
    <div class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-4">
      <Link href="/" class="text-lg font-bold tracking-tight text-foreground">
        LitoralHogar
      </Link>

      <nav class="hidden items-center gap-6 text-sm font-medium text-muted-foreground md:flex">
        <a href="#destacadas" class="transition-colors hover:text-foreground">Destacadas</a>
        <a href="/propiedades" class="transition-colors hover:text-foreground">Propiedades</a>
      </nav>

      <div class="flex items-center gap-2">
        <Link href="/login" class="rounded-lg border border-border px-4 py-2 text-sm font-semibold text-foreground transition-colors hover:bg-accent">
          Ingresar
        </Link>
        <Link href="/register" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground transition-colors hover:bg-primary/90">
          Registrarme
        </Link>
      </div>
    </div>
  </header>
</template>