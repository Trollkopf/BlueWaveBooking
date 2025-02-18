<template>
    <div class="flex h-screen bg-blue-100">
        <!-- Sidebar -->
        <div class="w-64 bg-blue-500 text-white shadow-md">
            <div class="p-4 text-xl font-bold border-b border-white">Admin Panel</div>
            <nav class="mt-4">
                <ul>
                    <li>
                        <Link class="block p-3 pl-5 hover:bg-blue-600" href="/dashboard">
                        📊 Dashboard
                        </Link>
                    </li>
                    <li>
                        <Link class="block p-3 pl-5 hover:bg-blue-600" href="/admin/hammocks">
                        🌴 Hamacas
                        </Link>
                    </li>
                    <li>
                        <Link class="block p-3 pl-5 hover:bg-blue-600" href="/admin/bookings">
                        📅 Reservas
                        </Link>
                    </li>
                    <li>
                        <Link class="block p-3 pl-5 hover:bg-blue-600" href="/admin/users">
                        👥 Usuarios
                        </Link>
                    </li>
                    <li>
                        <Link class="block p-3 pl-5 hover:bg-blue-600" href="/admin/managers">
                        🏢 Gerentes
                        </Link>
                    </li>
                    <li>
                        <Link class="block p-3 pl-5 hover:bg-blue-600" href="/admin/settings">
                        ⚙ Configuración
                        </Link>
                    </li>
                    <li>
                        <Link class="block p-3 pl-5 bg-red-600 hover:bg-red-700 text-white" method="post" as="button"
                            href="/logout">
                        🚪 Cerrar sesión
                        </Link>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Contenido Principal -->
        <div class="flex-1 p-6">
            <slot />
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: {
        Link,
    },
};
</script>

<style scoped>
/* Estilos opcionales */
</style>
