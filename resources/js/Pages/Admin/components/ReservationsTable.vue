<template>
    <div class="bg-white p-4 shadow-md rounded-lg">
      <h2 class="text-lg font-bold mb-3">Reservas Recientes</h2>
      <table class="w-full border-collapse border border-gray-300">
        <thead>
          <tr class="bg-blue-500 text-white">
            <th class="p-2 border border-gray-300">Usuario</th>
            <th class="p-2 border border-gray-300">Hamaca</th>
            <th class="p-2 border border-gray-300">Fecha</th>
            <th class="p-2 border border-gray-300">Horario</th>
            <th class="p-2 border border-gray-300">Estado</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="reservation in reservations" :key="reservation.id" class="text-center">
            <td class="p-2 border border-gray-300">{{ reservation.user.name }}</td>
            <td class="p-2 border border-gray-300">{{ reservation.hammock.name }}</td>
            <td class="p-2 border border-gray-300">{{ reservation.date }}</td>
            <td class="p-2 border border-gray-300">{{ formatTimeSlot(reservation.time_slot) }}</td>
            <td class="p-2 border border-gray-300" :class="getStatusClass(reservation.status)">{{ reservation.status }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>

  <script>
  export default {
    props: {
      reservations: {
        type: Array,
        required: true
      }
    },
    methods: {
      formatTimeSlot(slot) {
        const map = {
          morning: "Mañana",
          afternoon: "Tarde",
          full_day: "Día Completo"
        };
        return map[slot] || "Desconocido";
      },
      getStatusClass(status) {
        switch (status) {
          case 'pending': return 'text-yellow-500';
          case 'confirmed': return 'text-green-500';
          case 'cancelled': return 'text-red-500';
          default: return 'text-gray-500';
        }
      }
    }
  };
  </script>

  <style scoped>
  th, td {
    padding: 10px;
  }
  </style>
