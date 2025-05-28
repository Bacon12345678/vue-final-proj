<template>
  <div class="task-board__column">
    <h3 class="task-board__header">{{ title }}</h3>
    <div class="task-board__list" @dragover.prevent @drop="handleDrop">
      <slot></slot>
      <div
        class="task-board__input"
        contenteditable="true"
        ref="taskName"
        @keydown.enter.prevent="submit"
        @blur="handleBlur"
      >
        新增
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
const props = defineProps<{
  title: 'none' | 'todo' | 'doing' | 'done';
}>();

const taskName = ref<HTMLDivElement | null>(null);

const emit = defineEmits<{
  (e: 'drop', status: string): void;
  (e: 'submit', status: string, taskName: string): void;
}>();

const handleDrop = () => {
  emit('drop', props.title);
};

const submit = () => {
  const value = taskName.value?.innerText.trim();
  if (value) {
    emit('submit', value, props.title);
    taskName.value!.innerText = '新增';
    window.getSelection()?.removeAllRanges();
  }
  console.log(value);
};

const handleBlur = () => {
  // 若目前文字為預設值，或空值就不送
  const value = taskName.value?.innerText.trim();
  if (!value || value === '新增') {
    taskName.value!.innerText = '新增';
  }
};
</script>
<style scoped src="@/assets/scss/components/tasks.scss"></style>
