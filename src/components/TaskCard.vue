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
      <button v-if="!isEditing" @click="startEdit">
        <img :src="editPic" alt="編輯" />
      </button>

      <button v-if="isEditing" @click="openSideBoard">
        <img :src="sideboard" alt="開啟側欄" />
      </button>

      <button @click="openContextMenu($event.currentTarget as HTMLElement)">
        <img :src="morePic" alt="設定" />
      </button>
    </div>
  </div>
  <teleport to="body">
    <ContextMenu
      v-if="contextMenuVisable"
      class="pop-menu"
      :style="{ top: `${position.y}px`, left: `${position.x}px` }"
      @delete="deleteTask"
      @close="contextMenuVisable = false"
    />
  </teleport>

  <taskSideBoard v-if="sideBoardVisible" />
</template>

<script setup lang="ts">
import editPic from '@/assets/pic/edit.png';
import morePic from '@/assets/pic/more.png';
import sideboard from '@/assets/pic/sideboard.png';
import { ref, nextTick, reactive, onMounted, onUnmounted } from 'vue';
import ContextMenu from './ContextMenu.vue';
import taskSideBoard from '../views/taskSideBoard.vue';

const isEditing = ref(false);

const contextMenuVisable = ref(false);

const sideBoardVisible = ref(false);

const lastTriggerEl = ref<HTMLElement | null>(null);

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
  (e: 'update:list'): void;
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

const position = ref({
  x: 0,
  y: 0,
});

const openContextMenu = (targelEl: HTMLElement) => {
  if (!contextMenuVisable.value) {
    lastTriggerEl.value = targelEl;
    contextMenuVisable.value = true;
    const rect = targelEl.getBoundingClientRect();
    position.value = {
      x: rect.right + window.scrollX + 5,
      y: rect.bottom + window.scrollY + 5,
    };
  } else {
    contextMenuVisable.value = false;
    lastTriggerEl.value = null;
  }
};

const handleClickOutside = (e: MouseEvent) => {
  const menu = document.querySelector('.pop-menu');
  if (
    lastTriggerEl.value?.contains(e.target as Node) ||
    menu?.contains(e.target as Node)
  ) {
    return;
  } else {
    contextMenuVisable.value = false;
  }
};

const deleteTask = async () => {
  const res = await fetch('http://127.0.0.1/api/deleteTask.php', {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${localStorage.getItem('jwt_token')}`,
    },
    body: JSON.stringify({
      ID: props.task.ID,
    }),
  });

  console.log(res);

  emit('update:list');
};

const openSideBoard = () => {
  sideBoardVisible.value = true;
};

onMounted(() => {
  document.addEventListener('mousedown', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('mousedown', handleClickOutside);
});
</script>

<style scoped src="@/assets/scss/components/tasks.scss"></style>
<style scoped src="@/assets/scss/components/contextMenu.scss"></style>
