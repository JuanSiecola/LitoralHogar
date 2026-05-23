<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Phone, Mail, MapPin } from 'lucide-vue-next';
</script>

<template>
    <footer class="bg-muted">
        <div class="mx-auto max-w-7xl px-4 py-12">
            <div class="grid grid-cols-1 gap-10 md:grid-cols-3">
                <div class="flex flex-col gap-3">
                    <Link href="/" class="text-lg font-bold tracking-tight text-foreground">
                        LitoralHogar
                    </Link>
                    <p class="text-sm leading-relaxed text-muted-foreground">
                        Tu portal inmobiliario en Uruguay. Conectamos personas con su próximo hogar.
                    </p>
                </div>
                <div class="flex flex-col gap-3">
                    <h3 class="text-sm font-semibold text-foreground">Explorar</h3>
                    <nav class="flex flex-col gap-2 text-sm text-muted-foreground">
                        <Link href="/" class="transition-colors hover:text-foreground">Inicio</Link>
                        <Link href="/propiedades" class="transition-colors hover:text-foreground">Propiedades</Link>
                        <a href="/#destacadas" class="transition-colors hover:text-foreground">Destacadas</a>
                    </nav>
                </div>
                <div class="flex flex-col gap-3">
                    <h3 class="text-sm font-semibold text-foreground">Contacto</h3>
                    <ul class="flex flex-col gap-2 text-sm text-muted-foreground">
                        <li class="flex items-center gap-2">
                            <MapPin class="h-4 w-4 shrink-0 text-primary" />
                            Paysandú, Uruguay
                        </li>
                        <li class="flex items-center gap-2">
                            <Phone class="h-4 w-4 shrink-0 text-primary" />
                            <a href="tel:+59847200000" class="transition-colors hover:text-foreground">
                                +598 472 00000
                            </a>
                        </li>
                        <li class="flex items-center gap-2">
                            <Mail class="h-4 w-4 shrink-0 text-primary" />
                            <a href="mailto:contacto@litoralhogar.com" class="transition-colors hover:text-foreground">
                                contacto@litoralhogar.com
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-10 border-t border-border pt-6 text-center text-xs text-muted-foreground">
                © {{ new Date().getFullYear() }} LitoralHogar. Todos los derechos reservados.
            </div>
        </div>
    </footer>
</template>