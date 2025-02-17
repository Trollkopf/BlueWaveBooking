<template>
    <AdminSidebar>
        <slot />
    </AdminSidebar>
</template>

<script>
import AdminSidebar from '@/Components/AdminSidebar.vue';

export default {
    components: {
        AdminSidebar,
    },
};
</script>
