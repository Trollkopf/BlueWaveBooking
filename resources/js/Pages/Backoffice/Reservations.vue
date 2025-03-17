<template>
    <BackofficeLayout>
        <h2 class="text-2xl font-bold mb-4">Mis Reservas</h2>

        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-blue-500 text-white">
                    <th class="p-2 border">Hamaca</th>
                    <th class="p-2 border">Fecha</th>
                    <th class="p-2 border">Horario</th>
                    <th class="p-2 border">Estado</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="reservation in reservations" :key="reservation.id" class="text-center">
                    <td class="p-2 border">{{ reservation.hammock?.name || 'Sin datos' }}</td>
                    <td class="p-2 border">{{ reservation.date }}</td>
                    <td class="p-2 border">{{ formatTimeSlot(reservation.time_slot) }}</td>
                    <td class="p-2 border">{{ reservation.status }}</td>
                </tr>
            </tbody>
        </table>
    </BackofficeLayout>
</template>

<script>
import BackofficeLayout from '@/Layouts/BackofficeLayout.vue';
import axios from 'axios';

export default {
    components: { BackofficeLayout },
    data() {
        return { reservations: [] };
    },
    async mounted() {
        const response = await axios.get('/api/my-reservations');
        this.reservations = response.data.data;
    },
    methods: {
        formatTimeSlot(slot) {
            return { morning: "Mañana", afternoon: "Tarde", full: "Día Completo" }[slot] || "Desconocido";
        }
    }
};
</script>
