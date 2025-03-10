<template>
    <div class="bg-white p-4 shadow-md rounded-lg">
        <h2 class="text-lg font-bold mb-3">Reservas Recientes</h2>

        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-blue-500 text-white">
                    <th class="p-2 border border-gray-300">Usuario</th>
                    <th class="p-2 border border-gray-300">Hamaca</th>
                    <th class="p-2 border border-gray-300">Fecha</th>
                    <th class="p-2 border border-gray-300">Horario</th>
                    <th class="p-2 border border-gray-300">Estado</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="reservation in reservations.data" :key="reservation.id" class="text-center">
                    <td class="p-2 border border-gray-300">{{ reservation.user?.name || "Anónimo" }}</td>
                    <td class="p-2 border border-gray-300">{{ reservation.hammock?.name || "Sin datos" }}</td>
                    <td class="p-2 border border-gray-300">{{ reservation.date }}</td>
                    <td class="p-2 border border-gray-300">{{ formatTimeSlot(reservation.time_slot) }}</td>
                    <td class="p-2 border border-gray-300" :class="getStatusClass(reservation.status)">
                        {{ reservation.status }}
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="mt-4 flex justify-between items-center">
            <button @click="fetchReservations(reservations.prev_page_url)"
                    :disabled="!reservations.prev_page_url"
                    class="px-4 py-2 bg-gray-300 rounded disabled:opacity-50">
                ◀ Anterior
            </button>
            <span>Página {{ reservations.current_page }} de {{ reservations.last_page }}</span>
            <button @click="fetchReservations(reservations.next_page_url)"
                    :disabled="!reservations.next_page_url"
                    class="px-4 py-2 bg-gray-300 rounded disabled:opacity-50">
                Siguiente ▶
            </button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            reservations: {
                data: [],
                current_page: 1,
                last_page: 1,
                prev_page_url: null,
                next_page_url: null
            }
        };
    },
    mounted() {
        this.fetchReservations();
    },
    methods: {
        async fetchReservations(url = "/api/bookings") {
            try {
                const response = await axios.get(url);
                this.reservations = response.data;
            } catch (error) {
                console.error("Error cargando las reservas:", error);
            }
        },
        formatTimeSlot(slot) {
            return { morning: "Mañana", afternoon: "Tarde", full_day: "Día Completo" }[slot] || "Desconocido";
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
    padding: 10px;
}
</style>
