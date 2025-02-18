<template>
    <nav class="bg-blue-400 text-white p-4 flex justify-between items-center shadow-md">
        <div>
            <img src="/images/logo.png" alt="Logo" class="h-14">
        </div>
        <div>
            <label class="font-semibold mr-2">Selecciona el día:</label>
            <input type="date" v-model="localDate" class="border p-2 rounded text-black" @change="updateDate">
            <a href="/login" class="ml-4 bg-white text-blue-600 px-4 py-2 rounded-lg shadow hover:bg-gray-200 transition">
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
