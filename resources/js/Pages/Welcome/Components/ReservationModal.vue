<template>
    <div class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h3 class="text-lg font-bold mb-3">Reservar Hamaca</h3>

            <!-- Input para comentario -->
            <div class="mb-4">
                <label for="comment" class="block font-semibold">Comentario (Opcional):</label>
                <textarea v-model="comment" id="comment" class="border p-2 w-full rounded"
                    placeholder="Añadir comentario..."></textarea>
            </div>

            <!-- Botones de reserva con precios -->
            <div class="space-y-2">
                <button v-if="canReserve('morning')" @click="attemptReservation('morning')"
                    class="bg-yellow-500 text-white px-4 py-2 rounded w-full flex justify-between items-center">
                    Reservar Mañana <span class="text-sm font-bold">({{ prices.morning }}€)</span>
                </button>
                <button v-if="canReserve('afternoon')" @click="attemptReservation('afternoon')"
                    class="bg-orange-500 text-white px-4 py-2 rounded w-full flex justify-between items-center">
                    Reservar Tarde <span class="text-sm font-bold">({{ prices.afternoon }}€)</span>
                </button>
                <button v-if="canReserve('full')" @click="attemptReservation('full')"
                    class="bg-green-500 text-white px-4 py-2 rounded w-full flex justify-between items-center">
                    Reservar Día Completo <span class="text-sm font-bold">({{ prices.full }}€)</span>
                </button>
            </div>

            <!-- Botón para cerrar -->
            <button @click="$emit('closeModal')" class="mt-4 bg-gray-500 text-white px-4 py-2 rounded w-full">
                Cerrar
            </button>
        </div>

        <!-- Modal de Autenticación -->
        <div v-if="showAuthModal" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                <h3 class="text-lg font-bold mb-3">¿Quieres iniciar sesión?</h3>
                <p class="mb-4">Si inicias sesión, podrás gestionar tus reservas de forma más fácil.</p>

                <button @click="redirectToLogin" class="bg-blue-500 text-white px-4 py-2 rounded w-full mb-2">
                    🔑 Iniciar Sesión
                </button>

                <button @click="continueAsGuest" class="bg-gray-500 text-white px-4 py-2 rounded w-full">
                    👤 Continuar como Invitado
                </button>
            </div>
        </div>

        <!-- Modal para datos de invitado -->
        <div v-if="showGuestForm" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                <h3 class="text-lg font-bold mb-3">Introduce tus datos</h3>

                <label class="block font-semibold">Nombre:</label>
                <input v-model="guestData.name" type="text" class="border p-2 w-full rounded mb-2"
                    placeholder="Tu nombre">

                <label class="block font-semibold">Correo Electrónico:</label>
                <input v-model="guestData.email" type="email" class="border p-2 w-full rounded mb-2"
                    placeholder="tu@email.com">

                <label class="block font-semibold">Teléfono:</label>
                <input v-model="guestData.phone" type="tel" class="border p-2 w-full rounded mb-4"
                    placeholder="Número de teléfono">

                <button @click="confirmGuestReservation" class="bg-green-500 text-white px-4 py-2 rounded w-full">
                    Confirmar Reserva
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    props: ['selectedHammock', 'prices', 'selectedDate'],
    data() {
        return {
            comment: '',
            showAuthModal: false,
            showGuestForm: false,
            guestData: { name: '', email: '', phone: '' },
            pendingReservationType: null, // Guarda el tipo de reserva pendiente
            userAuthenticated: false, // Se actualizará dinámicamente
            user: null // Guarda datos del usuario autenticado
        };
    },
    async mounted() {
        await this.checkAuthStatus();
    },
    methods: {
        async checkAuthStatus() {
            try {
                const response = await axios.get('/api/user');
                this.userAuthenticated = !!response.data; // Si hay datos, el usuario está autenticado
                this.user = response.data;
            } catch (error) {
                this.userAuthenticated = false;
                this.user = null;
            }
        },
        getAvailability(hammock) {
            const allSlots = ['morning', 'afternoon', 'full'];
            const reserved = hammock.reservations;
            const available = allSlots.filter(slot => !reserved.includes(slot));

            const map = {
                morning: "Mañana",
                afternoon: "Tarde",
                full: "Día Completo"
            };

            if (reserved.includes('full')) {
                return 'No disponible';
            }

            if (available.length === 0) {
                return 'No disponible';
            }

            return 'Disponible: ' + available.map(s => map[s]).join(', ');
        },
        canReserve(slot) {
            const reserved = this.selectedHammock.reservations;

            // Si está reservado full, no puede reservar nada
            if (reserved.includes('full')) return false;

            // Si quiere reservar full, pero hay mañana o tarde, no se puede
            if (slot === 'full' && (reserved.includes('morning') || reserved.includes('afternoon'))) return false;

            // Si quiere reservar mañana pero ya está ocupada
            if (slot === 'morning' && reserved.includes('morning')) return false;

            // Si quiere reservar tarde pero ya está ocupada
            if (slot === 'afternoon' && reserved.includes('afternoon')) return false;

            return true; // está disponible
        },
        attemptReservation(type) {
            if (!this.userAuthenticated) {
                this.pendingReservationType = type; // Guarda el tipo de reserva pendiente
                this.showAuthModal = true; // Muestra la opción de iniciar sesión
            } else {
                this.makeReservation(type);
            }
        },
        redirectToLogin() {
            window.location.href = "/login"; // Redirige a la página de inicio de sesión
        },
        continueAsGuest() {
            this.showAuthModal = false;
            this.showGuestForm = true;
        },
        confirmGuestReservation() {
            if (!this.guestData.name || !this.guestData.email || !this.guestData.phone) {
                alert("Todos los campos son obligatorios para continuar como invitado.");
                return;
            }
            this.makeReservation(this.pendingReservationType, this.guestData);
            this.showGuestForm = false;
        },
        async makeReservation(type, guestData = null) {
            try {
                const payload = {
                    hammock_id: this.selectedHammock.id,
                    date: this.selectedDate,
                    type: type,
                    time_slot: type,
                    comment: this.comment || '',
                    status: 'pending',
                    price: this.prices[type] || 0
                };

                console.log(payload);

                // Si el usuario no está autenticado, agregar datos de invitado
                if (!this.userAuthenticated && guestData) {
                    payload.name = guestData.name;
                    payload.email = guestData.email;
                    payload.phone = guestData.phone;
                } else {
                    payload.user_id = this.user.id;
                }

                console.log("Enviando reserva con payload:", payload);

                const response = await axios.post('/api/bookings', payload);
                console.log("Reserva realizada con éxito:", response.data);
                this.$emit('refreshGrid'); // Notifica al padre que debe actualizarse el mapa de hamacas
                this.$emit('closeModal'); // Cierra el modal al confirmar la reserva

            } catch (error) {
                console.error("Error en la reserva:", error);
            }
        }
    }
};
</script>
