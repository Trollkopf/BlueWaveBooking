<template>
    <div class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h3 class="text-lg font-bold mb-3">Reservar Hamaca</h3>

            <p v-if="selectedHammock">Disponibilidad: {{ getAvailability(selectedHammock) }}</p>

            <!-- Input para comentario -->
            <div class="mb-4">
                <label for="comment" class="block font-semibold">Comentario (Opcional):</label>
                <textarea v-model="comment" id="comment" class="border p-2 w-full rounded"
                    placeholder="Añadir comentario..."></textarea>
            </div>

            <!-- Botones de reserva con precios -->
            <div class="space-y-2">
                <button v-if="canReserve('Tarde')" @click="makeReservation('morning')"
                    class="bg-yellow-500 text-white px-4 py-2 rounded w-full flex justify-between items-center">
                    Reservar Mañana <span class="text-sm font-bold">({{ prices.morning }}€)</span>
                </button>
                <button v-if="canReserve('Mañana')" @click="makeReservation('afternoon')"
                    class="bg-orange-500 text-white px-4 py-2 rounded w-full flex justify-between items-center">
                    Reservar Tarde <span class="text-sm font-bold">({{ prices.afternoon }}€)</span>
                </button>
                <button v-if="canReserve('full')" @click="makeReservation('full')"
                    class="bg-green-500 text-white px-4 py-2 rounded w-full flex justify-between items-center">
                    Reservar Día Completo <span class="text-sm font-bold">({{ prices.full }}€)</span>
                </button>
            </div>

            <!-- Botón para cerrar -->
            <button @click="$emit('closeModal')" class="mt-4 bg-gray-500 text-white px-4 py-2 rounded w-full">
                Cerrar
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: ['selectedHammock', 'prices'],
    data() {

        return {
            comment: ''
        };
    },
    methods: {
        getAvailability(hammock) {

            return hammock.reservations.length === 0 ? 'Disponible todo el día' : hammock.reservations.join(', ');

        },
        canReserve(type) {
            return !this.selectedHammock.reservations.includes(type);
        },
        makeReservation(type) {
            this.$emit('reserve', { type, comment: this.comment });
        }
    }
};
</script>
