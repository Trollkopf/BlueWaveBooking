<template>
    <div class="bg-white p-6 shadow-md rounded-lg">
        <h2 class="text-xl font-bold mb-4">Gestión de Gerentes</h2>

        <!-- Buscador -->
        <div class="mb-4 flex justify-between">
            <input type="text" v-model="searchQuery" placeholder="Buscar por nombre..."
                class="border p-2 rounded w-1/3" @input="debouncedFetchUsers">
        </div>

        <!-- Tabla de Usuarios -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="py-2 px-4 border">Nombre</th>
                        <th class="py-2 px-4 border">Email</th>
                        <th class="py-2 px-4 border">Rol</th>
                        <th class="py-2 px-4 border">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id" class="text-center">
                        <td class="py-2 px-4 border">{{ user.name }}</td>
                        <td class="py-2 px-4 border">{{ user.email }}</td>
                        <td class="py-2 px-4 border">{{ user.role === 'manager' ? 'Gerente' : 'Usuario' }}</td>
                        <td class="py-2 px-4 border">
                            <button @click="toggleManager(user)"
                                class="px-4 py-2 rounded text-white"
                                :class="user.role === 'manager' ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600'">
                                {{ user.role === 'manager' ? 'Revocar Gerente' : 'Convertir en Gerente' }}
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-4 flex justify-between">
            <button @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
                class="px-4 py-2 bg-gray-300 rounded disabled:opacity-50">
                ◀ Anterior
            </button>
            <span>Página {{ currentPage }} de {{ totalPages }}</span>
            <button @click="changePage(currentPage + 1)" :disabled="currentPage === totalPages"
                class="px-4 py-2 bg-gray-300 rounded disabled:opacity-50">
                Siguiente ▶
            </button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import debounce from 'lodash/debounce';


export default {
    data() {
        return {
            users: [],
            searchQuery: '',
            currentPage: 1,
            totalPages: 1,
        };
    },
    async mounted() {
        await this.fetchUsers();
    },
    methods: {
        async fetchUsers() {
            try {
                const response = await axios.get(`/api/users`, {
                    params: { page: this.currentPage, search: this.searchQuery }
                });
                this.users = response.data.data;
                this.totalPages = response.data.last_page;
            } catch (error) {
                console.error('Error al obtener usuarios:', error);
            }
        },
        async toggleManager(user) {
            try {
                const newRole = user.role === 'manager' ? 'user' : 'manager';
                await axios.put(`/api/users/${user.id}/role`, { role: newRole });
                this.fetchUsers(); // Recargar la lista después de la actualización
            } catch (error) {
                console.error('Error al cambiar el rol del usuario:', error);
            }
        },
        async changePage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
                await this.fetchUsers();
            }
        },
        debouncedFetchUsers: debounce(function() {
            this.fetchUsers();
        }, 500),
    }
};
</script>

<style scoped>
button:disabled {
    cursor: not-allowed;
}
</style>
