<template>
  <div class="taskBoard">
    <TaskColumn title="none" @drop="onDrop" @submit="addTaskToColumn">
      <div
        v-for="task in noneList"
        :key="task.ID"
        draggable="true"
        @dragstart="onDragStart(task.ID)"
        class="task-board__item"
      >
        {{ task.Name }}
      </div>
    </TaskColumn>
    <TaskColumn title="todo" @drop="onDrop" @submit="addTaskToColumn">
      <div
        v-for="task in todoList"
        :key="task.ID"
        draggable="true"
        @dragstart="onDragStart(task.ID)"
        class="task-board__item"
      >
        {{ task.Name }}
      </div>
    </TaskColumn>
    <TaskColumn title="doing" @drop="onDrop" @submit="addTaskToColumn">
      <div
        v-for="task in doingList"
        :key="task.ID"
        draggable="true"
        @dragstart="onDragStart(task.ID)"
        class="task-board__item"
      >
        {{ task.Name }}
      </div>
    </TaskColumn>

    <TaskColumn title="done" @drop="onDrop" @submit="addTaskToColumn">
      <div
        v-for="task in doneList"
        :key="task.ID"
        draggable="true"
        @dragstart="onDragStart(task.ID)"
        class="task-board__item"
      >
        {{ task.Name }}
      </div>
    </TaskColumn>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref, computed } from 'vue';
import TaskColumn from '../components/TaskColumn.vue';

interface Task {
  ID: number;
  Name: string;
  Color: string;
  Start_date: string;
  Due_date: string;
  Status: string;
  Create_at: string;
  Creator_ID: number;
  Updated_at: string | null;
}

//之後要改computed
const allTaskList = ref<Task[]>([]);

const noneList = computed(() =>
  allTaskList.value.filter((task) => task.Status === 'none')
);

const todoList = computed(() =>
  allTaskList.value.filter((task) => task.Status === 'todo')
);

const doingList = computed(() =>
  allTaskList.value.filter((task) => task.Status === 'doing')
);

const doneList = computed(() =>
  allTaskList.value.filter((task) => task.Status === 'done')
);

const dragginTask = ref<number | null>(null);

const onDragStart = (ID: number) => {
  dragginTask.value = ID;
};

const onDrop = (newStatus: string) => {
  const task = allTaskList.value.find((t) => t.ID === dragginTask.value);
  if (task) task.Status = newStatus;
  dragginTask.value = null;
};

const addTaskToColumn = async (taskName: string, status: string) => {
  try {
    const res = await fetch('http://127.0.0.1/api/addTasks.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        Name: taskName,
        Creator_ID: 1,
        Status: status,
      }),
    });

    const data = await res.json();

    if (data.success) {
      await fetchTaskList();
    } else {
      alert('新增失敗');
    }
  } catch (error) {
    console.error('新增任務失敗:', error);
  }
};

const fetchTaskList = async () => {
  try {
    const res = await fetch('http://127.0.0.1/api/getTasks.php');
    const data = await res.json();
    allTaskList.value = data.map((task: any) => ({
      ...task,
      Start_date: task.Start_date?.date ?? null,
      Due_date: task.Due_date?.date ?? null,
      Updated_at: task.Updated_at?.date ?? null,
    }));
  } catch (error) {
    console.error(error);
  }
};

onMounted(async () => {
  await fetchTaskList();
});
</script>

<style scoped src="@/assets/scss/components/tasks.scss"></style>
