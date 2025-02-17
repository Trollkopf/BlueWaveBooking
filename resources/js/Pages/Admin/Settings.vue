<template>
    <AdminLayout>
        <div class="container mt-4">
            <h1 class="text-xl font-bold mb-4">Gestión de Hamacas</h1>
            <Settings />
        </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import Settings from "@/Pages/Admin/components/AdminSettings.vue";

export default {
    components: {
        AdminLayout,
        Settings,
    },
};
</script>

<style scoped>
.container {
    padding: 20px;
}
</style>
