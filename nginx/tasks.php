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

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://ai-firefly.ru/api/tasks/get-user-tasks");
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
              <?php foreach ($task["responseFormat"] as $radio){
                if ($radio["content"] === "Свой ответ"){ ?>
                  <div class="radio-layout" style="flex-direction: column;">
                    <label><?php echo $radio["content"] ?></label>
                    <input type="text" placeholder="Свой ответ" class="res-input" name="<?php echo $taskKey . $subtaskKey ?>">
                  </div>
                <?php } else { ?>
                  <div class="radio-layout">
                    <input type="radio" value="<?php echo $radio["content"] ?>" name="<?php echo $taskKey . $subtaskKey ?>">
                    <label><?php echo $radio["content"] ?></label>
                  </div>
                <?php } ?>
              <?php } ?>
            </div>
            <?php } ?>
        </div>
        <p class="status-task"><?php echo $task["responseCount"] ?></p>
        <div class="task-event-layout">
          <button class="task-detailed-btn">Подробнее</button>
          <button class="task-save-btn" data-quanity-subtask="<?php echo count($task["subtasks"]) ?>" >
            <p>Сохранить</p>
            <span class="loader"></span>
          </button>
        </div>
      </div>
    <?php }} ?>
  </main>
</body>
<script src="js/header.js"></script>
<script>
  const taskLayout = $(".task-layout");
  const detailedBtn = $(".task-detailed-btn");
  const saveBtn = $(".task-save-btn");



  function eventDetailedTask(index){
    if (!$(taskLayout[index]).hasClass("active")){
      $(taskLayout[index]).addClass("active");
      $(saveBtn[index]).addClass("active");
      $(detailedBtn[index]).text("Скрыть");
    } else {
      $(taskLayout[index]).removeClass("active");
      $(saveBtn[index]).removeClass("active");
      $(detailedBtn[index]).text("Подробнее");
    }
  }

  function completeTask(index){
    let quanitySubtasks = parseInt($(saveBtn[index]).data("quanity-subtask"));
    let requireSubstacks = {};
    
    for (i = 0; i < quanitySubtasks; i++){
      let selectRadio = $(`input[name=${index}${i}]:checked`);
      console.log(i);

      if (!selectRadio.length){
        let input = $(`.res-input[name=${index}${i}]`).val();
        if (input){
          requireSubstacks[i] = input;
        }
        continue;
      }

      requireSubstacks[i] = selectRadio.val();
    }

    if (!Object.keys(requireSubstacks).length){
      eventLogModal("open", "cross", "Выделите ответы.");
      return;
    }

    $(saveBtn[index]).prop("disabled", true);
    $(saveBtn[index]).addClass("disabled");

    let data = {
      task_id: $(taskLayout[index]).data("task_id"),
      responseAnswer: requireSubstacks
    };

    $.ajax({
      url: "/api/tasks/complete-subtask",
      type: "PUT",
      contentType: "application/json",
      dataType: "json",
      data: JSON.stringify(data),

      success: (response) => {
        eventLogModal("open", "check", "Задание сохранено!");
        console.log(response);
        setTimeout(() => {
          location.reload();
        }, 1000);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        eventLogModal("open", "cross", "Ошибка на сервере.");
      },
      complete: function() {
        $(saveBtn[index]).removeClass("disabled");
      }
    });
  }



  detailedBtn.each((index, btn) => {
    $(btn).click(() => {
      eventDetailedTask(index);
    });
  });

  saveBtn.each((index, btn) => {
    $(btn).click(() => {
      completeTask(index);
    });
  });
</script>
</html>