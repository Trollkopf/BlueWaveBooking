<template>
    <nav class="bg-blue-400 text-white p-4 flex flex-col md:flex-row justify-between items-center shadow-md gap-4">
        <!-- Logo -->
        <div class="flex items-center gap-2">
            <img src="/images/logo.png" alt="Logo" class="h-14">
        </div>

        <!-- Leyenda de disponibilidad -->
        <div class="flex gap-3 items-center">Disponibilidad:
        <div class="flex gap-3 flex-wrap justify-center mt-2 md:mt-0 p-4 bg-blue-300 rounded-lg">
            <div class="flex items-center gap-1">
                <span class="w-4 h-4 rounded-full" style="background-color: #126EF5"></span>
                <span class="text-sm">Día completo</span>
            </div>
            <div class="flex items-center gap-1">
                <span class="w-4 h-4 rounded-full" style="background-color: #F31020"></span>
                <span class="text-sm">Mañana</span>
            </div>
            <div class="flex items-center gap-1">
                <span class="w-4 h-4 rounded-full" style="background-color: #FE9B11"></span>
                <span class="text-sm">Tarde</span>
            </div>
            <div class="flex items-center gap-1">
                <span class="w-4 h-4 rounded-full" style="background-color: #828282"></span>
                <span class="text-sm">Ocupado</span>
            </div>
        </div>
        </div>

        <!-- Selector de fecha + botón login -->
        <div class="flex items-center gap-4 flex-wrap justify-center">
            <div>
                <label class="font-semibold mr-2">Selecciona el día:</label>
                <input type="date" v-model="localDate" class="border p-2 rounded text-black" @change="updateDate">
            </div>
            <a href="/login"
               class="bg-white text-blue-600 px-4 py-2 rounded-lg shadow hover:bg-gray-200 transition">
                🔑 Iniciar sesión
            </a>
        </div>


    </nav>
</template>


<script>
export default {
    props: ['selectedDate'],
    data() {
        return {
            localDate: this.selectedDate
        };
    },
    methods: {
        updateDate() {
            this.$emit('updateDate', this.localDate);
        }
    }
};
</script>
