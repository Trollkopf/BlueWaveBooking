<template>
    <BackofficeLayout>
        <h2 class="text-2xl font-bold mb-4">Bienvenido, {{ user?.name }}</h2>

        <!-- Tarjeta de Información del Usuario -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold">Información de la Cuenta</h3>
            <p><strong>Email:</strong> {{ user?.email }}</p>
            <p><strong>Teléfono:</strong> {{ user?.phone || "No registrado" }}</p>
            <p><strong>Registrado hace:</strong> {{ daysRegistered }} días</p>
            <p><strong>Estado de la Cuenta:</strong>
                <span :class="getStatusClass(user?.status)">
                    {{ user?.status || "Activo" }}
                </span>
            </p>
        </div>

        <!-- Resumen de Actividad -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-200 p-4 rounded shadow">
                <h3 class="text-lg font-semibold">Total Reservas</h3>
                <p class="text-xl font-bold">{{ stats.total }}</p>
            </div>
            <div class="bg-green-200 p-4 rounded shadow">
                <h3 class="text-lg font-semibold">Últimos 7 días</h3>
                <p class="text-xl font-bold">{{ stats.lastWeek }}</p>
            </div>
            <div class="bg-yellow-200 p-4 rounded shadow">
                <h3 class="text-lg font-semibold">Últimos 30 días</h3>
                <p class="text-xl font-bold">{{ stats.lastMonth }}</p>
            </div>
        </div>

        <!-- Lista de Reservas -->
        <div class="bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-3">Mis Reservas</h3>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2 border">Fecha</th>
                        <th class="p-2 border">Horario</th>
                        <th class="p-2 border">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="reservation in reservations" :key="reservation.id" class="text-center">
                        <td class="p-2 border">{{ reservation.date }}</td>
                        <td class="p-2 border">{{ formatTimeSlot(reservation.time_slot) }}</td>
                        <td class="p-2 border" :class="getStatusClass(reservation.status)">
                            {{ reservation.status }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </BackofficeLayout>
</template>

<script>
import axios from 'axios';
import BackofficeLayout from '@/Layouts/BackofficeLayout.vue';

export default {
    components: { BackofficeLayout },
    data() {
        return {
            user: null,
            reservations: [],
            stats: {
                total: 0,
                lastWeek: 0,
                lastMonth: 0
            }
        };
    },
    async mounted() {
        await this.fetchUserData();
        await this.fetchReservations();
    },
    computed: {
        daysRegistered() {
            if (!this.user?.created_at) return "Desconocido";
            const registeredDate = new Date(this.user.created_at);
            const today = new Date();
            return Math.floor((today - registeredDate) / (1000 * 60 * 60 * 24));
        }
    },
    methods: {
        async fetchUserData() {
            try {
                const response = await axios.get('/api/user');
                this.user = response.data;
            } catch (error) {
                console.error("Error al cargar los datos del usuario:", error);
            }
        },
        async fetchReservations() {
            try {
                const response = await axios.get('/api/my-reservations');
                this.reservations = response.data.data;

                // Calcular estadísticas
                this.stats.total = this.reservations.length;
                const today = new Date();
                const lastWeek = new Date();
                lastWeek.setDate(today.getDate() - 7);
                const lastMonth = new Date();
                lastMonth.setDate(today.getDate() - 30);

                this.stats.lastWeek = this.reservations.filter(res => new Date(res.date) >= lastWeek).length;
                this.stats.lastMonth = this.reservations.filter(res => new Date(res.date) >= lastMonth).length;
            } catch (error) {
                console.error("Error al cargar las reservas:", error);
            }
        },
        formatTimeSlot(slot) {
            return { morning: "Mañana", afternoon: "Tarde", full: "Día Completo" }[slot] || "Desconocido";
        },
        getStatusClass(status) {
            return {
                pending: "text-yellow-500",
                confirmed: "text-green-500",
                cancelled: "text-red-500"
            }[status] || "text-gray-500";
        }
    }
};
</script>

<style scoped>
th, td {
    text-align: center;
    padding: 10px;
}
</style>
