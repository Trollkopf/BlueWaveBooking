<template>
    <div class="bg-white p-4 shadow-md rounded-lg relative">
        <h2 class="text-lg font-bold mb-3">Distribución de Hamacas</h2>

        <!-- Botón de guardar flotante arriba a la derecha -->
        <button v-if="isModified" @click="saveGridConfig"
            class="fixed top-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-full shadow-lg hover:bg-blue-600 transition z-50">
            💾 Guardar
        </button>

        <!-- Spinner de carga -->
        <div v-if="isSaving"
            class="fixed top-4 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white px-4 py-2 rounded-lg shadow-lg">
            Guardando...
        </div>

        <!-- Notificación de éxito -->
        <div v-if="showNotification"
            class="fixed top-12 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg">
            ¡Guardado exitosamente!
        </div>

        <!-- Configuración de filas y columnas -->
        <div class="flex gap-4 mb-4 items-center">
            <label>
                Filas:
                <input type="number" v-model.number="rows" min="1" class="border p-1 rounded" @change="updateGrid">
            </label>
            <label>
                Columnas:
                <input type="number" v-model.number="cols" min="1" class="border p-1 rounded" @change="updateGrid">
            </label>

            <!-- Leyenda de colores -->
            <div class="flex items-center gap-2 ml-4">
                <div class="w-6 h-6 bg-green-400 border rounded"></div>
                <span class="text-sm">2 Hamacas</span>
                <div class="w-6 h-6 bg-yellow-400 border rounded"></div>
                <span class="text-sm">1 Hamaca</span>
                <div class="w-6 h-6 bg-black text-white border rounded"></div>
                <span class="text-sm">Desactivado</span>
            </div>
        </div>

        <!-- Grid dinámico -->
        <div :style="gridStyle" class="grid gap-4">
            <div v-for="(cell, index) in grid" :key="index"
                class="p-4 border rounded-lg text-center font-semibold relative cursor-pointer transition-all flex items-center justify-center"
                :class="getCellClass(cell)" @click="toggleHammocks(cell)">
                <span class="text-lg font-bold">{{ getHammockLabel(cell.hammocks) }}</span>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            rows: 1,
            cols: 1,
            grid: [],
            gridMap: new Map(),
            isModified: false,
            isSaving: false,
            showNotification: false
        };
    },
    computed: {
        gridStyle() {
            return { gridTemplateColumns: `repeat(${this.cols}, minmax(100px, 1fr))` };
        }
    },
    async mounted() {
        await this.loadGridData();
    },
    methods: {
        async loadGridData() {
            try {
                const response = await axios.get("/api/hammock-spaces/backoffice");
                if (response.data.length > 0) {
                    this.gridMap = new Map(response.data.map(cell => [`${cell.row}-${cell.col}`, cell]));
                    this.rows = Math.max(...response.data.map(cell => cell.row)) + 1;
                    this.cols = Math.max(...response.data.map(cell => cell.col)) + 1;
                }
                this.updateGrid();
            } catch (error) {
                console.error("Error loading grid data:", error);
            }
        },
        updateGrid() {
            this.grid = [];
            for (let row = 0; row < this.rows; row++) {
                for (let col = 0; col < this.cols; col++) {
                    const key = `${row}-${col}`;
                    if (this.gridMap.has(key)) {
                        this.grid.push({ ...this.gridMap.get(key) });
                    } else {
                        this.grid.push({ id: null, row, col, hammocks: 2 });
                    }
                }
            }
            this.isModified = true;
        },
        getCellClass(cell) {
            return cell.hammocks === 0 ? "bg-black text-white" :
                cell.hammocks === 1 ? "bg-yellow-400 text-black" :
                    "bg-green-400 text-white";
        },
        getHammockLabel(hammocks) {
            return hammocks === 0 ? "D" : hammocks;
        },
        toggleHammocks(cell) {
            cell.hammocks = (cell.hammocks + 1) % 3;
            this.gridMap.set(`${cell.row}-${cell.col}`, cell);
            this.isModified = true;
        },
        async saveGridConfig() {
            this.isSaving = true;
            try {
                for (const [key, cell] of this.gridMap) {
                    const [row, col] = key.split('-').map(Number);
                    if (row >= this.rows || col >= this.cols) {
                        await axios.delete(`/api/hammock-spaces/${cell.id}`);
                        this.gridMap.delete(key);
                    }
                }

                for (const cell of this.grid) {
                    if (cell.id === null) {
                        const response = await axios.post("/api/hammock-spaces", cell);
                        cell.id = response.data.id;
                        this.gridMap.set(`${cell.row}-${cell.col}`, cell);
                    } else {
                        await axios.put(`/api/hammock-spaces/${cell.id}`, { hammocks: cell.hammocks });
                    }
                }
                this.isModified = false;
                this.showNotification = true;
                setTimeout(() => { this.showNotification = false; }, 3000);
                console.log("Grid saved successfully");
            } catch (error) {
                console.error("Error saving grid data:", error);
            } finally {
                this.isSaving = false;
            }
        }
    }
};
</script>

<style scoped>
.border {
    transition: all 0.3s ease-in-out;
}

.border:hover {
    transform: scale(1.05);
}
</style>
