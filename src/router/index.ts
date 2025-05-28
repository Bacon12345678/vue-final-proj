import { createRouter, createWebHistory } from 'vue-router';
import sideMenu from '../components/sideMenu.vue';
import tasks from '../views/tasks.vue';
import layout from '../views/layout.vue';
import DragArea from '../components/DragArea.vue';
import login from '../views/login.vue';

const routes = [
  {
    path: '/',
    component: layout,
    children: [
      {
        path: 'tasks',
        name: 'Tasks',
        component: tasks,
      },
      {
        path: 'test',
        name: 'Test',
        component: DragArea,
      },
    ],
  },
  {
    path: '/login',
    name: 'Login',
    component: login,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
