<template>
    <div class="grid-container bg-repeat bg-center"
        style="background-image: url('/images/arena.jpg'); background-size: auto 200px;">
        <div :style="gridStyle" class="grid gap-2 mt-4">
            <template v-for="rowIndex in rows" :key="rowIndex">
                <div v-for="colIndex in cols" :key="`${rowIndex}-${colIndex}`">
                    <template v-if="gridMap[`${rowIndex - 1}-${colIndex - 1}`]">
                        <button class="p-2" @click="$emit('openModal', gridMap[`${rowIndex - 1}-${colIndex - 1}`])">
                            <img :src="getHammockImage(gridMap[`${rowIndex - 1}-${colIndex - 1}`])" alt="Hamaca"
                                class="h-24 object-contain">
                        </button>
                    </template>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
export default {
    props: ['gridData'],
    computed: {
        gridMap() {
            const map = {};
            this.gridData.forEach(hammock => {
                map[`${hammock.row}-${hammock.col}`] = hammock;
            });
            return map;
        },
        rows() {
            return Math.max(...this.gridData.map(h => h.row), 1) + 1;
        },
        cols() {
            return Math.max(...this.gridData.map(h => h.col), 1) + 1;
        },
        gridStyle() {
            return { gridTemplateColumns: `repeat(${this.cols}, 1fr)` };
        }
    },
    methods: {
        getHammockImage(hammock) {
            if (!hammock || hammock.hammocks === 0) {
                return '/images/hammock0.png'; // Si no hay datos o no tiene hamacas, devuelve esta imagen
            }

            const type = hammock.hammocks === 2 ? 'hammock2' : 'hammock1';


            if (hammock.reservations.includes('full')) {
                return `/images/${type}f.png`; // Reservada todo el día
            } else if (hammock.reservations.includes('Mañana')) {
                return `/images/${type}m.png`; // Reservada por la mañana
            } else if (hammock.reservations.includes('Tarde')) {
                return `/images/${type}t.png`; // Reservada por la tarde
            } else {
                return `/images/${type}.png`; // Disponible
            }
        }
    }
};
</script>
