<?php

if (isset($_COOKIE["bearerToken"])){
  $session = true;
  $bearerToken = $_COOKIE["bearerToken"];
  $userLogin = $_COOKIE["userLogin"];
} else {
  $session = false;
  header("Location: /");
  exit;
}

$userTasks = file_get_contents("/api/users/get-user-tasks");

if ($userTasks === false){
  $userTasks = false;
} else {
  $userTasks = json_decode($userTasks, true);
  echo $userTasks[0]["name"];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/tasks.css">
  <title>Мои задания</title>
</head>
<body>
  <?php include("header.php") ?>
  <main class="tasks-main">
    <div class="task-layout">
      <h2 class="title-task">Логическая связность высказываний</h2>
      <p class="description-task">Оцени, логично ли связано второе предложение с первым. Задание на понимание причинно-следственных связей, особенно подходит для текстов с ИИ-анализом.</p>
      <div class="subtask-layout">
        <hr>
        <p>Первый факт: "Пользователь забыл сохранить документ перед выключением компьютера."<br>Второй факт: "Файл был автоматически отправлен в облако и восстановился при включении."</p>
        <div class="task-response-layout">
          <div class="radio-layout">
            <input type="radio" value="Логично" disabled>
            <label>Логично</label>
          </div>
          <div class="radio-layout">
            <input type="radio" value="Возможно, логично, но не очевидно" disabled>
            <label>Возможно, логично, но не очевидно</label>
          </div>
          <div class="radio-layout">
            <input type="radio" value="Не логично" disabled>
            <label>Не логично</label>
          </div>
          <div class="radio-layout">
            <input type="radio" value="Свой ответ" disabled>
            <label>Свой ответ</label>
          </div>
        </div>
      </div>
      <p class="status-task">Выполнено 0/1</p>
      <div class="task-event-layout">
        <button class="task-detailed-btn">Подробнее</button>
      </div>
    </div>
  </main>
</body>
<script src="js/header.js"></script>
<script>
  const taskLayout = $(".task-layout");
  const detailedBtn = $(".task-detailed-btn");



  function eventDetailedTask(index){
    if (!$(taskLayout[index]).hasClass("active")){
      $(taskLayout[index]).addClass("active");
      $(detailedBtn[index]).text("Скрыть");
    } else {
      $(taskLayout[index]).removeClass("active");
      $(detailedBtn[index]).text("Подробнее");
    }
  }



  detailedBtn.each((index, btn) => {
    $(btn).click(() => {
      eventDetailedTask(index);
    })
  })
</script>
</html>