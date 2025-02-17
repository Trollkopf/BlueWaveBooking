<template>
    <div class="relative min-h-screen">
        <!-- Navegación superior -->
        <nav class="bg-blue-600 text-white p-4 flex justify-between items-center shadow-md">
            <img src="/images/logo.png" alt="Logo" class="h-10">
            <div>
                <label class="font-semibold mr-2">Selecciona el día:</label>
                <input type="date" v-model="selectedDate" class="border p-2 rounded text-black" @change="loadHammocks">
            </div>
        </nav>

        <!-- Textura del mar -->
        <div class="bg-repeat bg-top w-full h-24"
            style="background-image: url('/images/mar.jpg'); background-size: 300px auto;"></div>

        <!-- Contenedor del mapa de hamacas con textura de arena -->
        <div class="bg-repeat bg-center p-6 grid-container"
            style="background-image: url('/images/arena.jpg'); background-size: 300px auto;">
            <!-- Aviso de cierre -->
            <div v-if="isClosed" class="text-center bg-red-500 text-white p-2 rounded">
                📢 El servicio está cerrado en esta fecha.
            </div>

            <!-- Mapa de hamacas -->
            <div v-else :style="gridStyle" class="grid gap-2 mt-4">
                <template v-for="(row, rowIndex) in rows" :key="rowIndex">
                    <div v-for="(col, colIndex) in cols" :key="`${rowIndex}-${colIndex}`">
                        <template v-if="gridMap[`${rowIndex}-${colIndex}`]">
                            <button class="p-2" @click="openReservationModal(gridMap[`${rowIndex}-${colIndex}`])">
                                <img :src="getHammockImage(gridMap[`${rowIndex}-${colIndex}`])" alt="Hamaca"
                                    class="h-24 object-contain">
                            </button>
                        </template>
                        <template v-else>
                            <div class="w-16 h-16"></div> <!-- Espacio vacío -->
                        </template>
                    </div>
                </template>
            </div>
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
            rows: 0,
            cols: 0,
            gridMap: {},
            showModal: false,
            selectedHammock: null,
        };
    },
    computed: {
        gridStyle() {
            return { gridTemplateColumns: `repeat(${this.cols}, 1fr)` };
        }
    },
    async mounted() {
        await this.loadHammocks();
    },
    methods: {
        async loadHammocks() {
            try {
                const response = await axios.get(`/api/hammock-spaces?date=${this.selectedDate}`);
                this.isClosed = response.data.isClosed;

                this.gridMap = {};
                response.data.hammocks.forEach(hammock => {
                    this.gridMap[`${hammock.row}-${hammock.col}`] = hammock;
                });

                this.rows = Math.max(...response.data.hammocks.map(h => h.row)) + 1;
                this.cols = Math.max(...response.data.hammocks.map(h => h.col)) + 1;
            } catch (error) {
                console.error("Error loading hammocks:", error);
            }
        },
        getHammockImage(hammock) {
            if (hammock.hammocks === 1) {
                return '/images/hammock1.png'
            } else if (hammock.hammocks === 2) { return '/images/hammock2.png' }
            else{
                return '/images/hammock0.png';
            };
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
                this.loadHammocks();
            } catch (error) {
                console.error("Error reservando:", error);
            }
        }
    }
};
</script>

<style scoped>
.grid-container {
    min-height: calc(100vh - 140px);
    display: flex;
    justify-content: center;
    align-items: center;
}

.grid button {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px;
    cursor: pointer;
    transition: transform 0.2s;
    background: none;
    border: none;
}

.grid button:hover {
    transform: scale(1.1);
}
</style>
