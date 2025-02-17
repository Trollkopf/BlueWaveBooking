<template>
    <AdminLayout>
        <div class="container mt-4">
            <h1 class="text-xl font-bold mb-4">Listado</h1>
            <UsersList />
        </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import UsersList from "@/Pages/Admin/components/UsersList.vue";

export default {
    components: {
        AdminLayout,
        UsersList,
    },
};
</script>

<style scoped>
.container {
    padding: 20px;
}
</style>
