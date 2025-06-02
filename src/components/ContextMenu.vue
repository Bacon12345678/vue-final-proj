<template>
  <div class="menu">
    <button class="menu__btn">指派給</button>
    <button class="menu__btn danger" @click="handleDelete">刪除</button>
  </div>
</template>

<script setup lang="ts">
const emit = defineEmits<{
  delete: () => void;
  close: () => void;
}>();

const handleDelete = () => {
  emit('delete');
  emit('close');
};
</script>

<style scoped>
.menu {
  width: 160px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  padding: 8px;
  border: 1px solid #e5e7eb;
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.menu__btn {
  all: unset;
  padding: 8px 12px;
  border-radius: 6px;
  font-size: 14px;
  color: #374151;
  cursor: pointer;
  transition: background 0.2s;
}

.menu__btn:hover {
  background: #f3f4f6;
}

.menu__btn.danger {
  color: #dc2626;
}

.menu__btn.danger:hover {
  background: #fee2e2;
}
</style>
