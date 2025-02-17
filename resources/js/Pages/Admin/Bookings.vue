<template>
    <AdminLayout>
        <div class="container mt-4">
            <h1 class="text-xl font-bold mb-4">Listado de Reservas</h1>
            <BookingList />
        </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import BookingList from "@/Pages/Admin/components/ReservationsList.vue";

export default {
    components: {
        AdminLayout,
        BookingList,
    },
};
</script>

<style scoped>
.container {
    padding: 20px;
}
</style>
