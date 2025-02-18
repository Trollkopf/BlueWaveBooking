<template>
    <div class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h3 class="text-lg font-bold mb-3">Reservar Hamaca</h3>
            <p v-if="selectedHammock">Disponibilidad: {{ getAvailability(selectedHammock) }}</p>
            <button v-if="canReserve('morning')" @click="$emit('reserve', 'morning')"
                class="bg-yellow-500 text-white px-4 py-2 rounded w-full">Reservar Mañana</button>
            <button v-if="canReserve('afternoon')" @click="$emit('reserve', 'afternoon')"
                class="bg-orange-500 text-white px-4 py-2 rounded w-full mt-2">Reservar Tarde</button>
            <button v-if="canReserve('full')" @click="$emit('reserve', 'full')"
                class="bg-green-500 text-white px-4 py-2 rounded w-full mt-2">Reservar Día Completo</button>
            <button @click="$emit('closeModal')" class="mt-4 bg-gray-500 text-white px-4 py-2 rounded w-full">Cerrar</button>
        </div>
    </div>
</template>

<script>
export default {
    props: ['selectedHammock'],
    methods: {
        getAvailability(hammock) {
            return hammock.reservations.length === 0 ? 'Disponible todo el día' : hammock.reservations.join(', ');
        },
        canReserve(type) {
            return !this.selectedHammock.reservations.includes(type);
        }
    }
};
</script>
