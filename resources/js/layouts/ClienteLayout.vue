<template>
  <div class="flex min-h-screen bg-gray-50">
    
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md flex flex-col">
      <div class="p-6 border-b">
        <h2 class="text-lg font-bold text-blue-800">Mi Panel</h2>
        <p class="text-sm text-gray-500">{{ $page.props.auth.user.email }}</p>
        <Link href="/"
              class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-50 text-gray-700">
           Volver
        </Link>
      </div>
      <nav class="flex-1 p-4 space-y-1">
        <Link href="/cliente/dashboard"
              class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-50 text-gray-700">
           Dashboard
        </Link>
        <Link href="/cliente/favoritos"
              class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-50 text-gray-700">
           Mis Favoritos
        </Link>
        <Link href="/cliente/consultas"
              class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-blue-50 text-gray-700">
           Mis Consultas
        </Link>
      </nav>
    </aside>

    <!-- Contenido principal -->
    <main class="flex-1 p-8">
      <slot />
    </main>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>