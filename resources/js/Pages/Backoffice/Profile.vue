<template>
    <BackofficeLayout>
        <h2 class="text-2xl font-bold mb-4">Editar Perfil</h2>

        <form @submit.prevent="updateProfile" class="space-y-4">
            <div>
                <label class="block font-semibold">Nombre</label>
                <input v-model="user.name" type="text" class="border p-2 w-full rounded">
            </div>
            <div>
                <label class="block font-semibold">Email</label>
                <input v-model="user.email" type="email" class="border p-2 w-full rounded" disabled>
            </div>
            <div>
                <label class="block font-semibold">Teléfono</label>
                <input v-model="user.phone" type="text" class="border p-2 w-full rounded">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar Cambios</button>
        </form>
    </BackofficeLayout>
</template>

<script>
import BackofficeLayout from '@/Layouts/BackofficeLayout.vue';
import axios from 'axios';

export default {
    components: { BackofficeLayout },
    data() {
        return {
            user: { name: '', email: '', phone: '' }
        };
    },
    async mounted() {
        const response = await axios.get('/api/user');
        this.user = response.data;
    },
    methods: {
        async updateProfile() {
            try {
                await axios.put('/api/user/update', this.user);
                alert('Perfil actualizado con éxito');
            } catch (error) {
                console.error("Error al actualizar:", error);
            }
        }
    }
};
</script>
