<template>
    <AdminLayout>
        <div class="container">
            <GestionGerentes />
        </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import GestionGerentes from "@/Pages/Admin/components/GestionGerentes.vue";

export default {
    components: {
        AdminLayout,
        GestionGerentes,
    },
};
</script>

<style scoped>
.container {
    padding: 20px;
}
</style>
