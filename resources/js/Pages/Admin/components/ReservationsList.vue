<template>
    <div class="bg-white p-4 shadow-md rounded-lg">
        <h2 class="text-lg font-bold mb-3">Reservas de Hoy ({{ todayDate }})</h2>

        <!-- Botones de Filtros -->
        <div class="flex gap-4 mb-4">
            <button @click="fetchReservations('/api/bookings/upcoming')"
                    class="px-4 py-2 bg-green-500 text-white rounded">
                📅 Ver Próximas Reservas
            </button>
            <button @click="showHistoryModal = true"
                    class="px-4 py-2 bg-blue-500 text-white rounded">
                📜 Historial de Reservas
            </button>
        </div>

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
                <tr v-for="reservation in filteredReservations" :key="reservation.id" class="text-center">
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

        <!-- Modal de Historial de Reservas -->
        <div v-if="showHistoryModal" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                <h3 class="text-lg font-bold mb-3">Historial de Reservas</h3>

                <!-- Formulario de Búsqueda -->
                <label class="block font-semibold">Buscar por Fecha:</label>
                <input v-model="searchDate" type="date" class="border p-2 w-full rounded mb-2">

                <button @click="searchReservations" class="bg-blue-500 text-white px-4 py-2 rounded w-full">
                    🔍 Buscar
                </button>

                <button @click="showHistoryModal = false" class="mt-4 bg-gray-500 text-white px-4 py-2 rounded w-full">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</template>

---

### 📌 **Script actualizado**
```vue
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
            },
            todayDate: new Date().toISOString().split('T')[0], // Obtiene la fecha actual en formato YYYY-MM-DD
            showHistoryModal: false,
            searchDate: '',
        };
    },
    computed: {
        filteredReservations() {
            // Filtra solo las reservas de hoy
            return this.reservations.data.filter(res => res.date === this.todayDate);
        }
    },
    mounted() {
        this.fetchReservations();
    },
    methods: {
        async fetchReservations(url = "/api/bookings/today") {
            try {
                const response = await axios.get(url);
                this.reservations = response.data;
            } catch (error) {
                console.error("Error cargando las reservas:", error);
            }
        },
        async searchReservations() {
            if (!this.searchDate) return;

            try {
                const response = await axios.get(`/api/bookings?date=${this.searchDate}`);
                this.reservations = response.data;
                this.showHistoryModal = false;
            } catch (error) {
                console.error("Error en la búsqueda de reservas:", error);
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
