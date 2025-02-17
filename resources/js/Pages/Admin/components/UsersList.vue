<template>
    <div class="bg-white p-4 shadow-md rounded-lg">
        <h2 class="text-lg font-bold mb-3">Listado de Usuarios</h2>

        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2">ID</th>
                    <th class="border border-gray-300 px-4 py-2">Nombre</th>
                    <th class="border border-gray-300 px-4 py-2">Email</th>
                    <th class="border border-gray-300 px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id">
                    <td class="border border-gray-300 px-4 py-2">{{ user.id }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ user.name }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ user.email }}</td>
                    <td class="border border-gray-300 px-4 py-2 flex gap-2">
                        <button @click="viewReservations(user)" class="bg-blue-500 text-white px-2 py-1 rounded">📜
                            Historial</button>
                        <button @click="deleteUser(user.id)" class="bg-red-400 text-white px-2 py-1 rounded">🗑️
                            Eliminar</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Modal de Historial de Reservas -->
        <div v-if="showModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                <h3 class="text-lg font-bold mb-3">Historial de Reservas</h3>
                <ul v-if="reservations.length > 0">
                    <li v-for="reservation in reservations" :key="reservation.id" class="border-b py-2">
                        {{ reservation.date }} - {{ getTypeLabel(reservation.type) }}
                    </li>
                </ul>
                <p v-else>No hay reservas para este usuario.</p>
                <button @click="showModal = false" class="mt-4 bg-gray-500 text-white px-4 py-2 rounded">Cerrar</button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            users: [],
            showModal: false,
            reservations: []
        };
    },
    async mounted() {
        await this.loadUsers();
    },
    methods: {
        async loadUsers() {
            try {
                const response = await axios.get("/api/users");
                this.users = response.data;
            } catch (error) {
                console.error("Error loading users:", error);
            }
        },
        async viewReservations(user) {
            try {
                const response = await axios.get(`/api/users/${user.id}/reservations`);
                this.reservations = response.data;
                this.showModal = true;
            } catch (error) {
                console.error("Error loading reservations:", error);
            }
        },
        async deleteUser(id) {
            if (confirm("¿Estás seguro de eliminar este usuario? Esto también eliminará sus reservas.")) {
                try {
                    await axios.delete(`/api/users/${id}`);
                    this.users = this.users.filter(user => user.id !== id);
                } catch (error) {
                    console.error("Error deleting user:", error);
                }
            }
        },
        getTypeLabel(type) {
            return type === 'full' ? 'Día Completo' : type === 'morning' ? 'Mañana' : 'Tarde';
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
