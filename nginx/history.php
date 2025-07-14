<?php

if (isset($_COOKIE["bearerToken"])){
  $session = true;
  $bearerToken = $_COOKIE["bearerToken"];
  $userLogin = $_COOKIE["userLogin"];

  require("check-role.php");
} else {
  $session = false;
  header("Location: /");
  exit;
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://ai-firefly.ru/api/tasks/get-history");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIE, "userLogin=" . $_COOKIE["userLogin"]);
$userTasks = curl_exec($ch);

if (curl_error($ch)){
  $userTasks = false;
} else {
  $userTasks = json_decode($userTasks, true);
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
  <title>История</title>
</head>
<body>
  <?php include("header.php") ?>
  <main class="tasks-main">
    <div class="create-task-layout">
    </div>
    <?php if ($userTasks !== false){ foreach ($userTasks as $taskKey => $task){ ?>
      <div class="task-layout" data-task_id="<?php echo $task['_id'] ?>">
        <h2 class="title-task"><?php echo $task["name"] ?></h2>
        <p class="description-task"><?php echo $task["description"] ?></p>
        <div class="subtask-layout">
          <?php foreach ($task["subtasks"] as $subtaskKey => $subtask){
            ?>
            <hr>
            <p><?php echo $subtask["description"] ?></p>
            <div class="task-response-layout">
                  <div class="radio-layout">
                    <input type="radio" value="<?php echo $subtask["content"] ?>" disabled checked>
                    <label><?php echo $subtask["content"] ?></label>
                  </div>
            </div>
            <?php } ?>
        </div>
        <p class="status-task">Исполнитель: <?php echo $task["userLogin"] ?></p>
        <p class="status-task">Выполнено: <?php echo $task["responseCount"] ?></p>
        <div class="task-event-layout">
          <button class="task-detailed-btn">Подробнее</button>
        </div>
      </div>
    <?php }} ?>
  </main>
</body>
<script src="js/header.js"></script>
<script>
  const taskLayout = $(".task-layout");
  const detailedBtn = $(".task-detailed-btn");
  const deleteBtn = $(".task-delete-btn");
  const createBtn = $(".create-task-btn");



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
    });
  });
</script>
</html>