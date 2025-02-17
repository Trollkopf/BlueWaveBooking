<template>
    <div class="relative bg-blue-200 p-6 rounded-lg shadow-lg">
        <!-- Selector de fecha -->
        <div class="absolute top-2 left-2 bg-white p-2 rounded shadow-md">
            <label class="font-semibold">Selecciona el día:</label>
            <input type="date" v-model="selectedDate" class="border p-2 rounded" @change="loadHammocks">
        </div>

        <!-- Aviso de cierre -->
        <div v-if="isClosed" class="text-center bg-red-500 text-white p-2 rounded">
            📢 El servicio está cerrado en esta fecha.
        </div>

        <!-- Mapa de hamacas -->
        <div v-else :style="gridStyle" class="grid gap-2 mt-16">
            <button v-for="(hammock, index) in grid" :key="index" :class="getHammockClass(hammock)"
                @click="openReservationModal(hammock)">
                <img :src="getHammockImage(hammock)" alt="Hamaca">
            </button>
        </div>

        <!-- Modal de reserva -->
        <div v-if="showModal" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                <h3 class="text-lg font-bold mb-3">Reservar Hamaca</h3>
                <p v-if="selectedHammock">Disponibilidad: {{ getAvailability(selectedHammock) }}</p>

                <button v-if="canReserve('morning')" @click="reserve('morning')"
                    class="bg-yellow-500 text-white px-4 py-2 rounded w-full">Reservar Mañana</button>
                <button v-if="canReserve('afternoon')" @click="reserve('afternoon')"
                    class="bg-orange-500 text-white px-4 py-2 rounded w-full mt-2">Reservar Tarde</button>
                <button v-if="canReserve('full')" @click="reserve('full')"
                    class="bg-green-500 text-white px-4 py-2 rounded w-full mt-2">Reservar Día Completo</button>

                <button @click="showModal = false"
                    class="mt-4 bg-gray-500 text-white px-4 py-2 rounded w-full">Cerrar</button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            selectedDate: new Date().toISOString().split('T')[0],
            isClosed: false,
            grid: [],
            showModal: false,
            selectedHammock: null,
        };
    },
    computed: {
        gridStyle() {
            return { gridTemplateColumns: `repeat(6, 1fr)` };
        }
    },
    async mounted() {
        await this.loadHammocks();
    },
    methods: {
        async loadHammocks() {
            try {
                const response = await axios.get(`/api/hammock-spaces?date=${this.selectedDate}`);
                this.grid = response.data.hammocks;
                this.isClosed = response.data.isClosed;
            } catch (error) {
                console.error("Error loading hammocks:", error);
            }
        },
        getHammockClass(hammock) {
            return hammock.hammocks === 1 ? 'bg-yellow-300 p-2 rounded' : 'bg-green-400 p-2 rounded';
        },
        getHammockImage(hammock) {
            return hammock.hammocks === 1 ? '/images/hammock1.png' : '/images/hammock2.png';
        },
        openReservationModal(hammock) {
            this.selectedHammock = hammock;
            this.showModal = true;
        },
        getAvailability(hammock) {
            return hammock.reservations.length === 0 ? 'Disponible todo el día' : hammock.reservations.join(', ');
        },
        canReserve(type) {
            if (!this.selectedHammock) return false;
            return !this.selectedHammock.reservations.includes(type);
        },
        async reserve(type) {
            try {
                await axios.post('/api/bookings', { hammock_id: this.selectedHammock.id, date: this.selectedDate, type });
                this.showModal = false;
                this.loadHammocks(); // Recargar disponibilidad
            } catch (error) {
                console.error("Error reservando:", error);
            }
        }
    }
};
</script>

<style scoped>
.grid button {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px;
    cursor: pointer;
    transition: transform 0.2s;
}

.grid button:hover {
    transform: scale(1.1);
}
</style>
