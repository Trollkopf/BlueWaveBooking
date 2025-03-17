<template>
    <div class="p-6 bg-gray-100 min-h-screen">
        <h1 class="text-2xl font-bold mb-4">Área de Clientes</h1>

        <div class="grid grid-cols-2 gap-4">
            <!-- Tarjetas de Información -->
            <div class="bg-white p-4 shadow rounded">
                <h2 class="text-lg font-semibold">Mis Reservas</h2>
                <p>Total: {{ totalReservations }}</p>
            </div>

            <div class="bg-white p-4 shadow rounded">
                <h2 class="text-lg font-semibold">Estado de Cuenta</h2>
                <p>{{ accountStatus }}</p>
            </div>
        </div>

        <button @click="logout" class="mt-6 bg-red-500 text-white px-4 py-2 rounded">
            Cerrar Sesión
        </button>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            totalReservations: 0,
            accountStatus: "Activo"
        };
    },
    async mounted() {
        await this.fetchUserData();
    },
    methods: {
        async fetchUserData() {
            try {
                const response = await axios.get('/api/user-dashboard');
                this.totalReservations = response.data.totalReservations;
            } catch (error) {
                console.error("Error cargando datos del usuario:", error);
            }
        },
        async logout() {
            await axios.post("/logout");
            window.location.href = "/";
        }
    }
};
</script>
