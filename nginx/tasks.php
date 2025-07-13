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
      <div class="task-layout">
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
                <input type="radio" value="<?php echo $radio["content"] ?>" name="<?php echo $taskKey . $subtaskKey ?>">
                <label><?php echo $radio["content"] ?></label>
              </div>
              <?php } ?>
            </div>
            <?php } ?>
        </div>
        <p class="status-task"><?php echo $task["responseCount"] ?></p>
        <div class="task-event-layout">
          <button class="task-detailed-btn">Подробнее</button>
          <button class="task-save-btn" data-quanity-subtask="<?php echo count($task["subtasks"]) ?>" >Сохранить</button>
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
    let quanitySubtasks = $(saveBtn[index]).data("quanity-subtask");
    
    for (i = 0, i < quanitySubtasks, i++){
      let selectRadio = $(`input[name=${index}${i}]:checked`);

      if (!selectRadio.length){
        continue;
      }

      alert(selectRadio.val())
    }
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