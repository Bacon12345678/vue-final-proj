<template>
  <div class="task-board__item" draggable="true" @dragstart="handleDragStart">
    <template v-if="!isEditing">
      <div class="task-board__content">
        {{ task.Name }}
      </div>
    </template>

    <template v-else>
      <div
        class="task-board__content"
        contenteditable="true"
        ref="taskName"
        @keydown.enter.prevent="submit"
        @blur="submit"
      >
        {{ task.Name }}
      </div>
    </template>

    <div class="task-board__actions">
      <button @click="startEdit">
        <img :src="editPic" alt="編輯" />
      </button>
      <button @click="$emit('more', task)">
        <img :src="morePic" alt="設定" />
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import editPic from '@/assets/pic/edit.png';
import morePic from '@/assets/pic/more.png';
import { ref, nextTick } from 'vue';

const isEditing = ref(false);

// ✅ 修正 props 型別（number 要小寫）
const props = defineProps<{
  task: {
    ID: number;
    Name: string;
  };
}>();

const taskName = ref<HTMLElement | null>(null);

// ✅ 正確定義 emits
const emit = defineEmits<{
  (e: 'dragstart', ID: number): void;
  (e: 'edit', task: { ID: number; Name: string }): void;
  (e: 'more', task: { ID: number; Name: string }): void;
}>();

// ✅ 修正名稱錯誤 handel → handle
const handleDragStart = () => {
  emit('dragstart', props.task.ID);
};

const startEdit = () => {
  isEditing.value = true;
  nextTick(() => {
    if (taskName.value) {
      const range = document.createRange();
      const sel = window.getSelection();
      range.selectNodeContents(taskName.value);
      range.collapse(false); // 游標到最後
      sel?.removeAllRanges();
      sel?.addRange(range);
      taskName.value.focus();
    }
  });
};

const submit = () => {
  const value = taskName.value?.innerText.trim();
  console.log(value);
};
</script>

<style scoped src="@/assets/scss/components/tasks.scss"></style>
