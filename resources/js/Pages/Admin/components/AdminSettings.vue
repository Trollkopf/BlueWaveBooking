<template>
    <div class="bg-white p-6 shadow-md rounded-lg">
        <h2 class="text-lg font-bold mb-4">Configuración de Reservas</h2>

        <div class="grid grid-cols-2 gap-4">
            <!-- Horario de Mañana -->
            <div>
                <label class="block font-semibold">Horario de Mañana</label>
                <div class="flex gap-2">
                    <input type="time" v-model="settings.morning_start" class="border p-2 rounded w-1/2">
                    <input type="time" v-model="settings.morning_end" class="border p-2 rounded w-1/2">
                </div>
                <label class="block mt-2">Precio (€)</label>
                <input type="number" v-model.number="settings.price_morning" class="border p-2 rounded w-full">
            </div>

            <!-- Horario de Tarde -->
            <div>
                <label class="block font-semibold">Horario de Tarde</label>
                <div class="flex gap-2">
                    <input type="time" v-model="settings.afternoon_start" class="border p-2 rounded w-1/2">
                    <input type="time" v-model="settings.afternoon_end" class="border p-2 rounded w-1/2">
                </div>
                <label class="block mt-2">Precio (€)</label>
                <input type="number" v-model.number="settings.price_afternoon" class="border p-2 rounded w-full">
            </div>

            <!-- Horario Día Completo -->
            <div>
                <label class="block font-semibold">Horario Día Completo</label>
                <div class="flex gap-2">
                    <input type="time" v-model="settings.full_day_start" class="border p-2 rounded w-1/2">
                    <input type="time" v-model="settings.full_day_end" class="border p-2 rounded w-1/2">
                </div>
                <label class="block mt-2">Precio (€)</label>
                <input type="number" v-model.number="settings.price_full_day" class="border p-2 rounded w-full">
            </div>

            <!-- Fechas de Cierre -->
            <div>
                <label class="block font-semibold">Fechas de Cierre</label>
                <div class="flex gap-2">
                    <input type="date" v-model="settings.closed_from" class="border p-2 rounded w-1/2">
                    <input type="date" v-model="settings.closed_to" class="border p-2 rounded w-1/2">
                </div>
            </div>
        </div>

        <button @click="saveSettings" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">💾 Guardar</button>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            settings: {
                morning_start: "08:00",
                morning_end: "12:00",
                afternoon_start: "12:00",
                afternoon_end: "18:00",
                full_day_start: "08:00",
                full_day_end: "18:00",
                price_morning: 10,
                price_afternoon: 15,
                price_full_day: 25,
                closed_from: "",
                closed_to: ""
            }
        };
    },
    async mounted() {
        await this.loadSettings();
    },
    methods: {
        async loadSettings() {
            try {
                const response = await axios.get("/api/settings");
                this.settings = response.data;
            } catch (error) {
                console.error("Error loading settings:", error);
            }
        },
        async saveSettings() {
            try {
                await axios.put("/api/settings", this.settings);
                alert("Configuración guardada con éxito.");
            } catch (error) {
                console.error("Error saving settings:", error);
            }
        }
    }
};
</script>

<style scoped>
input {
    display: block;
    width: 100%;
    margin-top: 4px;
}
</style>
