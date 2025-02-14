<template>
    <div class="bg-white p-4 shadow-md rounded-lg">
      <h2 class="text-lg font-bold mb-3">Estado de las Hamacas</h2>
      <div class="grid grid-cols-3 gap-4">
        <div
          v-for="hammock in hammocks"
          :key="hammock.id"
          class="p-3 rounded-lg text-center font-semibold"
          :class="getStatusClass(hammock.status)">
          {{ hammock.name }}
        </div>
      </div>
    </div>
  </template>

  <script>
  export default {
    props: {
      hammocks: {
        type: Array,
        required: true
      }
    },
    methods: {
      getStatusClass(status) {
        switch (status) {
          case 'available': return 'bg-green-400 text-white';
          case 'reserved': return 'bg-yellow-400 text-black';
          case 'unavailable': return 'bg-red-400 text-white';
          default: return 'bg-gray-300';
        }
      }
    }
  };
  </script>

  <style scoped>
  .p-3 {
    transition: all 0.3s ease-in-out;
  }
  </style>
