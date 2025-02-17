<template>
    <div class="bg-white p-4 shadow-md rounded-lg flex items-center space-x-4">
        <div class="text-3xl">{{ icon }}</div>
        <div>
            <h3 class="text-lg font-semibold">{{ title }}</h3>
            <p class="text-2xl font-bold">{{ value }}</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            required: true
        },
        value: {
            type: [String, Number],
            required: true
        },
        icon: {
            type: String,
            required: true
        }
    }
};
</script>

<style scoped>
.bg-white {
    transition: transform 0.2s ease-in-out;
}

.bg-white:hover {
    transform: scale(1.05);
}
</style>
