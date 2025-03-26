<template>
    <AdminLayout>
        <div class="p-6 bg-gray-100 min-h-screen">
            <h1 class="text-2xl font-bold mb-4">Panel de Administración</h1>

            <!-- Totales en 3 niveles (Día, Semana, Mes) -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <!-- Totales del Día -->
                <div class="bg-white p-4 shadow rounded">
                    <h2 class="text-lg font-semibold">Hoy</h2>
                    <p>Total Reservas: {{ totals.today.reservations }}</p>
                    <p>Ingresos Totales: {{ totals.today.income }} €</p>
                    <p>Usuarios Registrados: {{ totals.today.users }}</p>
                </div>
                <div class="bg-white p-4 shadow rounded">
                    <h2 class="text-lg font-semibold">Última Semana</h2>
                    <p>Total Reservas: {{ totals.week.reservations }}</p>
                    <p>Ingresos Totales: {{ totals.week.income }} €</p>
                    <p>Usuarios Registrados: {{ totals.week.users }}</p>
                </div>
                <div class="bg-white p-4 shadow rounded">
                    <h2 class="text-lg font-semibold">Último Mes</h2>
                    <p>Total Reservas: {{ totals.month.reservations }}</p>
                    <p>Ingresos Totales: {{ totals.month.income }} €</p>
                    <p>Usuarios Registrados: {{ totals.month.users }}</p>
                </div>
            </div>

            <!-- Tabla de Reservas Recientes -->
            <div class="bg-white p-4 shadow rounded">
                <h2 class="text-lg font-semibold mb-3">Reservas Recientes</h2>
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="py-2 px-4 border">Usuario</th>
                            <th class="py-2 px-4 border">Fecha</th>
                            <th class="py-2 px-4 border">Horario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="reservation in recentReservations" :key="reservation.id" class="text-center">
                            <td class="py-2 px-4 border">{{ reservation.user }}</td>
                            <td class="py-2 px-4 border">{{ reservation.date }}</td>
                            <td class="py-2 px-4 border">{{ reservation.time_slot }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import axios from "axios";

export default {
        components: {
            AdminLayout
        },
        data() {
            return {
                totals: {
                    today: { reservations: 0, available: 0, users: 0 },
                    week: { reservations: 0, available: 0, users: 0 },
                    month: { reservations: 0, available: 0, users: 0 }
                },
                recentReservations: []
            };
        },
        async mounted() {
            await this.fetchDashboardData();
        },
        methods: {
            async fetchDashboardData() {
                try {
                    const response = await axios.get('/api/admin/dashboard');
                    this.totals = response.data.totals;
                    this.recentReservations = response.data.recentReservations;
                } catch (error) {
                    console.error("Error al cargar los datos del dashboard:", error);
                }
            }
        }
    };
</script>

<style scoped>
.container {
    padding: 20px;
}

th,
td {
    text-align: center;
}
</style>
