<template>
    <div class="grid-container">
        <div :style="gridStyle" class="grid gap-2 mt-4">
            <template v-for="(row, rowIndex) in Math.max(rows, 1)" :key="rowIndex">
                <div v-for="(col, colIndex) in Math.max(cols, 1)" :key="`${rowIndex}-${colIndex}`">
                    <template v-if="gridMap[`${rowIndex}-${colIndex}`]">
                        <button class="p-2" @click="$emit('openModal', gridMap[`${rowIndex}-${colIndex}`])">
                            <img :src="getHammockImage(gridMap[`${rowIndex}-${colIndex}`])" alt="Hamaca" class="h-24 object-contain">
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
            return Math.max(...this.gridData.map(h => h.row)) + 1;
        },
        cols() {
            return Math.max(...this.gridData.map(h => h.col)) + 1;
        },
        gridStyle() {
            return { gridTemplateColumns: `repeat(${this.cols}, 1fr)` };
        }
    },
    methods: {
        getHammockImage(hammock) {
            return hammock.hammocks === 1 ? '/images/hammock1.png' :
                   hammock.hammocks === 2 ? '/images/hammock2.png' :
                   '/images/hammock0.png';
        }
    }
};
</script>
