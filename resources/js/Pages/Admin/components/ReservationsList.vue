<template>
    <div class="bg-white p-4 shadow-md rounded-lg">

        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2">Fecha</th>
                    <th class="border border-gray-300 px-4 py-2">Usuario</th>
                    <th class="border border-gray-300 px-4 py-2">Tipo</th>
                    <th class="border border-gray-300 px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="reservation in reservations" :key="reservation.id">
                    <td class="border border-gray-300 px-4 py-2">{{ reservation.date }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ reservation.user }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ getTypeLabel(reservation.type) }}</td>
                    <td class="border border-gray-300 px-4 py-2 flex gap-2">
                        <button @click="editReservation(reservation)"
                            class="bg-blue-500 text-white px-2 py-1 rounded">✏️</button>
                        <button @click="deleteReservation(reservation.id)"
                            class="bg-red-500 text-white px-2 py-1 rounded">🗑️</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            reservations: []
        };
    },
    async mounted() {
        await this.loadReservations();
    },
    methods: {
        async loadReservations() {
            try {
                const response = await axios.get("/api/reservations");
                this.reservations = response.data;
            } catch (error) {
                console.error("Error loading reservations:", error);
            }
        },
        getTypeLabel(type) {
            return type === 'full' ? 'Día Completo' : type === 'morning' ? 'Mañana' : 'Tarde';
        },
        editReservation(reservation) {
            console.log("Editar reserva", reservation);
            // Aquí puedes abrir un modal para editar la reserva
        },
        async deleteReservation(id) {
            if (confirm("¿Estás seguro de eliminar esta reserva?")) {
                try {
                    await axios.delete(`/api/reservations/${id}`);
                    this.reservations = this.reservations.filter(res => res.id !== id);
                } catch (error) {
                    console.error("Error deleting reservation:", error);
                }
            }
        }
    }
};
</script>

<style scoped>
th,
td {
    text-align: center;
}
</style>
