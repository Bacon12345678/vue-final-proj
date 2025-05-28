<template>
  <div class="drag-area" @mousedown="startSelect" ref="containerRef">
    <div
      v-for="item in items"
      :key="item.id"
      ref="itemRefs"
      class="drag-item"
      :class="{ selected: selectedIds.includes(item.id) }"
    >
      {{ item.name }}
    </div>
    <div
      class="selection-box"
      v-show="isSelecting"
      :style="selectionBoxStyle"
    ></div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, computed } from 'vue';

const items = ref([
  { id: 1, name: '項目 1' },
  { id: 2, name: '項目 2' },
  { id: 3, name: '項目 3' },
]);

const isSelecting = ref(false);
const selectedIds = ref<number[]>([]);

const itemRefs = ref<HTMLElement[]>([]);

const startPoint = reactive({ x: 0, y: 0 });
const currentPoint = reactive({ x: 0, y: 0 });

const startSelect = (e: MouseEvent) => {
  isSelecting.value = true;
  selectedIds.value = [];
  startPoint.x = e.pageX;
  startPoint.y = e.pageY;

  currentPoint.x = e.pageX;
  currentPoint.y = e.pageY;

  document.addEventListener('mousemove', onMouseMove);
  document.addEventListener('mouseup', endSelect);
};

const onMouseMove = (e: MouseEvent) => {
  currentPoint.x = e.pageX;
  currentPoint.y = e.pageY;
  updateSelection();
};

const containerRef = ref<HTMLElement | null>(null);

const selectionBoxStyle = computed(() => {
  const rect = containerRef.value?.getBoundingClientRect();
  if (!rect) return {};

  const x = Math.min(startPoint.x, currentPoint.x) - rect.left;
  const y = Math.min(startPoint.y, currentPoint.y) - rect.top;
  const width = Math.abs(startPoint.x - currentPoint.x);
  const height = Math.abs(startPoint.y - currentPoint.y);
  return {
    left: `${x}px`,
    top: `${y}px`,
    width: `${width}px`,
    height: `${height}px`,
  };
});

const updateSelection = () => {
  const rect = getSelectRect();
  selectedIds.value = [];

  itemRefs.value.forEach((el, index) => {
    const elRect = el.getBoundingClientRect();

    if (
      elRect.left < rect.right &&
      elRect.top < rect.bottom &&
      elRect.right > rect.left &&
      elRect.bottom > rect.top
    ) {
      selectedIds.value.push(items.value[index].id);
    }
  });
};

const endSelect = () => {
  isSelecting.value = false;
  selectedIds.value = [];
  document.removeEventListener('mousemove', onMouseMove);
  document.removeEventListener('mouseup', endSelect);
};

const getSelectRect = () => {
  const x1 = Math.min(startPoint.x, currentPoint.x);
  const y1 = Math.min(startPoint.y, currentPoint.y);
  const x2 = Math.max(startPoint.x, currentPoint.x);
  const y2 = Math.max(startPoint.y, currentPoint.y);
  return { left: x1, top: y1, right: x2, bottom: y2 };
};
</script>

<style>
.drag-area {
  position: relative;
  user-select: none;
}

.drag-item {
  width: 100px;
  height: 50px;
  background: #eee;
  border: 1px solid #ccc;
  margin: 10px;
}

.drag-item.selected {
  background: lightblue;
}

.selection-box {
  position: absolute;
  border: 1px dashed #333;
  background-color: rgba(100, 100, 255, 0.2);
  pointer-events: none;
}
</style>
