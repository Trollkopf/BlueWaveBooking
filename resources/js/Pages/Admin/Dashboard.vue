<template>
    <AdminLayout>
      <div class="container mt-4">
        <h1 class="text-xl font-bold mb-4">Panel de Administración</h1>

        <!-- Sección de estadísticas -->
        <div class="grid grid-cols-3 gap-4">
          <StatsCard title="Total Reservas" :value="totalBookings" icon="📅" />
          <StatsCard title="Hamacas Disponibles" :value="availableHammocks" icon="🌴" />
          <StatsCard title="Usuarios Registrados" :value="totalUsers" icon="👥" />
        </div>

        <!-- Tabla de Reservas Recientes -->
        <div class="mt-6">
          <ReservationsTable :reservations="recentBookings" />
        </div>

        <!-- Estado de las hamacas -->
        <div class="mt-6">
          <HammocksStatus :hammocks="hammocksStatus" />
        </div>
      </div>
    </AdminLayout>
  </template>

  <script>
  import AdminLayout from "@/Layouts/AdminLayout.vue";
  import StatsCard from "@/Pages/Admin/components/StatsCard.vue";
  import ReservationsTable from "@/Pages/Admin/components/ReservationsTable.vue";
  import HammocksStatus from "@/Pages/Admin/components/HammocksStatus.vue";
  import axios from "axios";

  export default {
    components: {
      AdminLayout,
      StatsCard,
      ReservationsTable,
      HammocksStatus,
    },
    data() {
      return {
        totalBookings: 0,
        availableHammocks: 0,
        totalUsers: 0,
        recentBookings: [],
        hammocksStatus: [],
      };
    },
    async mounted() {
      this.fetchDashboardData();
    },
    methods: {
      async fetchDashboardData() {
        try {
          const response = await axios.get("/api/admin/dashboard");
          this.totalBookings = response.data.totalBookings;
          this.availableHammocks = response.data.availableHammocks;
          this.totalUsers = response.data.totalUsers;
          this.recentBookings = response.data.recentBookings;
          this.hammocksStatus = response.data.hammocksStatus;
        } catch (error) {
          console.error("Error al obtener datos del dashboard", error);
        }
      },
    },
  };
  </script>

  <style scoped>
  .container {
    padding: 20px;
  }
  </style>
