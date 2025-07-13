<?php

if (isset($_COOKIE["bearerToken"])){
  $session = true;
  $bearerToken = $_COOKIE["bearerToken"];
  $userLogin = $_COOKIE["userLogin"];

  require("check-role.php");

  if ($userRole !== "ROLE_ADMIN"){
    header("Location: /");
    exit;
  }
} else {
  $session = false;
  header("Location: /");
  exit;
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://ai-firefly.ru/api/tasks/get-creater-task");
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
  <title>Мои задания</title>
</head>
<body>
  <?php include("header.php") ?>
  <main class="tasks-main">
    <?php if ($userTasks !== false){ foreach ($userTasks as $taskKey => $task){ ?>
      <div class="task-layout" data-task_id="<?php echo $task['_id'] ?>">
        <h2 class="title-task"><?php echo $task["name"] ?></h2>
        <p class="description-task"><?php echo $task["description"] ?></p>
        <div class="subtask-layout">
          <?php foreach ($task["subtasks"] as $subtaskKey => $subtask){
            if (isset($subtask["content"])){
              continue;
            }
            ?>
            <hr>
            <p><?php echo $subtask["description"] ?></p>
            <div class="task-response-layout">
              <?php foreach ($task["responseFormat"] as $radio){ ?>
                  <div class="radio-layout">
                    <input type="radio" value="<?php echo $radio["content"] ?>" name="<?php echo $taskKey . $subtaskKey ?>" disabled>
                    <label><?php echo $radio["content"] ?></label>
                  </div>
              <?php } ?>
            </div>
            <?php } ?>
        </div>
        <p class="status-task"><?php echo $task["responseCount"] ?></p>
        <div class="task-event-layout">
          <button class="task-detailed-btn">Подробнее</button>
          <button class="task-delete-btn">Удалить</button>
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



  function eventDetailedTask(index){
    if (!$(taskLayout[index]).hasClass("active")){
      $(taskLayout[index]).addClass("active");
      $(detailedBtn[index]).text("Скрыть");
    } else {
      $(taskLayout[index]).removeClass("active");
      $(detailedBtn[index]).text("Подробнее");
    }
  }

  function deleteTask(index){

    $(deleteBtn[index]).prop("disabled", true);
  
    let data = {
      id: $(taskLayout[index]).data("task_id")
    };

    $.ajax({
      url: "/api/tasks/delete-task",
      type: "DELETE",
      contentType: "application/json",
      dataType: "json",
      data: JSON.stringify(data),

      success: (response) => {
        eventLogModal("open", "check", "Задание удалено.");
        setTimeout(() => {
          location.reload();
        }, 1000);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        eventLogModal("open", "cross", "Ошибка на сервере.");
      },
      complete: function() {
        $(saveBtn[index]).prop("disabled", false);
      }
    });
  }



  detailedBtn.each((index, btn) => {
    $(btn).click(() => {
      eventDetailedTask(index);
    });
  });

  deleteBtn.each((index, btn) => {
    $(btn).click(() => {
      deleteTask(index);
    });
  });
</script>
</html>