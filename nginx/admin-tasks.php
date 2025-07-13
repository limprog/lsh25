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
    <div class="create-task-layout">
      <button class="create-task-btn">
        <div class="create-task-text">Новое задание</div>
        <div class="create-task-icon">
          <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path d="M19.4367 7.82483L16.5279 2.64278C15.7857 1.32136 14.4102 0.5 12.9384 0.5H7.06331C5.59143 0.5 4.21513 1.32136 3.47379 2.64278L0.564109 7.82483C-0.188036 9.16612 -0.188036 10.833 0.564109 12.176L3.47379 17.3581C4.21596 18.6795 5.5906 19.5 7.06248 19.5H12.9375C14.4094 19.5 15.7849 18.6795 16.527 17.3581L19.4359 12.1752C20.188 10.833 20.188 9.16612 19.4359 7.82397L19.4367 7.82483ZM13.3364 10.8641H10.8432V13.4551C10.8432 13.9327 10.4716 14.3188 10.0121 14.3188C9.55245 14.3188 9.18095 13.9327 9.18095 13.4551V10.8641H6.68765C6.22805 10.8641 5.85655 10.4772 5.85655 10.0004C5.85655 9.52368 6.22805 9.13676 6.68765 9.13676H9.18095V6.54573C9.18095 6.06898 9.55245 5.68205 10.0121 5.68205C10.4716 5.68205 10.8432 6.06898 10.8432 6.54573V9.13676H13.3364C13.796 9.13676 14.1675 9.52368 14.1675 10.0004C14.1675 10.4772 13.796 10.8641 13.3364 10.8641Z"/>
          </svg>
        </div>
      </button>
    </div>
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
        <p class="status-task">Исполнитель: <?php echo $task["userLogin"] ?></p>
        <p class="status-task">Выполнено: <?php echo $task["responseCount"] ?></p>
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
  const createBtn = $("create-task-btn");



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

    $.ajax({
      url: `/api/tasks/delete-task?id=${$(taskLayout[index]).data("task_id")}`,
      type: "DELETE",

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

  createBtn.click(() => {
    window.location = "/";
  })
</script>
</html>