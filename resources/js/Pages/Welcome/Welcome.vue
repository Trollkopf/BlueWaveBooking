<template>
    <div class="relative min-h-screen bg-repeat bg-center grid-container"
        style="background-image: url('/images/arena.jpg'); background-size: auto 200px;">
        <!-- Barra de navegación -->
        <NavBar :selectedDate="selectedDate" @updateDate="updateDate" />

        <!-- Fondos de mar y arena -->
        <BeachBackground />

        <!-- Aviso de cierre -->
        <div v-if="isClosed" class="text-center bg-red-500 text-white p-2 rounded">
            📢 El servicio está cerrado en esta fecha.
        </div>

        <!-- Mapa de hamacas -->
        <HammockGrid :gridData="gridData" @openModal="openReservationModal" />

        <!-- Modal de reserva -->
        <ReservationModal v-if="showModal" :selectedHammock="selectedHammock" :prices='this.prices'
            @closeModal="showModal = false" @reserve="reserve" />
    </div>
</template>

<script>
import axios from 'axios';
import NavBar from './Components/NavBar.vue';
import BeachBackground from './Components/BeachBackground.vue';
import HammockGrid from './Components/HammockGrid.vue';
import ReservationModal from './Components/ReservationModal.vue';

export default {
    components: { NavBar, BeachBackground, HammockGrid, ReservationModal },
    data() {
        return {
            selectedDate: new Date().toISOString().split('T')[0],
            isClosed: false,
            gridData: [],
            showModal: false,
            selectedHammock: null,
            prices: {},
        };
    },
    async mounted() {
        await this.loadHammocks();
    },
    methods: {
        async loadHammocks() {
            try {
                const response = await axios.get(`/api/hammock-spaces?date=${this.selectedDate}`);
                this.isClosed = response.data.isClosed;
                this.gridData = response.data.hammocks || [];
                this.prices = response.data.prices || {};

            } catch (error) {
                console.error("Error loading hammocks:", error);
            }
        },
        updateDate(newDate) {
            this.selectedDate = newDate;
            this.loadHammocks();
        },
        openReservationModal(hammock) {
            this.selectedHammock = hammock;
            this.showModal = true;
        },
        async reserve(type) {
            try {
                await axios.post("/api/bookings", {
                    hammock_id: this.selectedHammock.id,
                    date: this.selectedDate,
                    time_slot: type.type,
                    comment: type.comment,
                    type: type.type,
                }); this.showModal = false;
                this.loadHammocks();
            } catch (error) {
                console.error("Error reservando:", error);
            }
        }
    }
};
</script>
