<template>
    <AdminLayout>
        <div class="container mt-4">
            <h1 class="text-xl font-bold mb-4">Gestión de Hamacas</h1>
            <HammocksGrid />
        </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import HammocksGrid from "@/Pages/Admin/components/HammocksGrid.vue";

export default {
    components: {
        AdminLayout,
        HammocksGrid,
    },
};
</script>

<style scoped>
.container {
    padding: 20px;
}
</style>
