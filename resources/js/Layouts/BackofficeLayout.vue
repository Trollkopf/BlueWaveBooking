<template>
    <div class="min-h-screen flex flex-col">
        <!-- Navbar -->
        <nav class="bg-blue-500 text-white p-4 flex justify-between">
            <h1 class="text-lg font-bold">Panel de Cliente</h1>
            <button @click="logout" class="bg-red-500 px-4 py-2 rounded">Cerrar Sesión</button>
        </nav>

        <div class="flex flex-1">
            <!-- Sidebar -->
            <aside class="w-64 bg-blue-500 text-white h-screen p-6">
        <nav>
            <ul class="space-y-4">
                <li>
                    <Link :href="route('backoffice.dashboard')"
                        class="flex items-center gap-3 p-3 rounded-lg transition-all hover:bg-blue-600"
                        >
                        📊 Dashboard
                    </Link>
                </li>
                <li>
                    <Link :href="route('backoffice.profile')"
                        class="flex items-center gap-3 p-3 rounded-lg transition-all hover:bg-blue-600"
                        :class="{ 'bg-blue-500': $page.url.startsWith('/backoffice/profile') }">
                        👤 Perfil
                    </Link>
                </li>
                <li>
                    <Link :href="route('backoffice.reservations')"
                        class="flex items-center gap-3 p-3 rounded-lg transition-all hover:bg-blue-600"
                        :class="{ 'bg-blue-500': $page.url.startsWith('/backoffice/reservations') }">
                        📅 Mis Reservas
                    </Link>
                </li>
            </ul>
        </nav>
    </aside>


            <!-- Contenido Dinámico -->
            <main class="flex-1 p-6">
                <slot />
            </main>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import { Link } from '@inertiajs/vue3';


export default {
    components: {
        Link,
    },
    methods: {
        async logout() {
            await axios.post('/logout');
            window.location.href = '/';
        }
    }
};
</script>
