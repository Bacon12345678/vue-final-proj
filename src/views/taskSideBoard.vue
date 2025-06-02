<template>
  <teleport to="body">
    <div class="task-board">
      <h1>你好</h1>
    </div>
  </teleport>
</template>

<style scoped>
.task-board {
  height: 100vh;
  width: 30%;
  background-color: rgb(62, 62, 62);
  position: absolute;
  z-index: 999;
  right: 0;
  top: 0;
}
</style>
