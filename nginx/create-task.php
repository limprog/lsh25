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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создание задания</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/create-task.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<?php include("header.php") ?>
<body>
  <main class="creating-main">
    <div class="creating-row">
      <p class="creating-title-input">Логин исполнителя</p>
      <input type="text" id="userLogin" placeholder="Логин исполнителя" class="creating-input" required>
    </div>
    <div class="creating-row">
      <p class="creating-title-input">Название задания</p>
      <input type="text" id="taskName" placeholder="Название задания" class="creating-input" required>
    </div>
    <div class="creating-row">
      <p class="creating-title-input">Описание задания</p>
      <textarea id="taskDescription" placeholder="Описание задания" class="creating-input" rows="4" required></textarea>
    </div>

    <hr>

    <div id="subtasksContainer" class="items-layout">
      <p class="creating-title-input">Задачи</p>
      <div class="subtask-item">
        <div class="creating-row">
          <input type="text" class="subtask-description creating-input" placeholder="Описание задачи" required>
        </div>
        <button type="button" class="remove-item-button">Удалить задачу</button>
      </div>
    </div>
    <button type="button" id="addSubtaskButton" class="add-item-btn">Добавить задачу</button>

    <hr>

    <div id="responseFormatContainer" class="items-layout">
      <p class="creating-title-input">Содержание ответа</p>
      <div class="response-format-item">
        <div class="creating-row">
          <input type="text" class="response-content creating-input" placeholder="Содержание ответа" required>
        </div>
        <button type="button" class="remove-item-button" >Удалить поле ответа</button>
      </div>
    </div>
    <button type="button" id="addResponseFormatButton" class="add-item-btn">Добавить поле ответа</button>

    <button type="submit" class="add-item-btn">Создать задание</button>
  </main>

  <script src="js/header.js"></script>
  <script>
    $(document).ready(function() {
      // Добавление подзадачи
      $('#addSubtaskButton').on('click', function() {
        const newItem = `
        <div class="subtask-item">
            <div class="creating-row">
                <input type="text" class="subtask-description creating-input" placeholder="Описание задачи" required>
            </div>
            <button type="button" class="remove-item-button">Удалить задачу</button>
        </div>
        `;
        $('#subtasksContainer').append(newItem);
      });

      // Добавление поля ответа
      $('#addResponseFormatButton').on('click', function() {
        const newItem = `
            <div class="response-format-item">
                <div class="creating-row">
                    <input type="text" class="response-content creating-input" placeholder="Содержание ответа" required>
                </div>
                <button type="button" class="remove-item-button">Удалить поле ответа</button>
            </div>
        `;
        $('#responseFormatContainer').append(newItem);
      });

      // Удаление элемента (подзадача или поле ответа)
      // Используем делегирование событий, так как элементы добавляются динамически
      $(document).on('click', '.remove-item-button', function() {
        $(this).closest('.subtask-item, .response-format-item').remove();
      });

      // Логика для сбора данных из формы и формирования JSON
      $('button[type="submit"]').on('click', function(e) {
        e.preventDefault(); // Предотвращаем стандартную отправку формы

        const task = {
          userLogin: $('#userLogin').val(),
          name: $('#taskName').val(),
          description: $('#taskDescription').val(),
          subtasks: [],
          responseFormat: [],
          score: 0
        };

        // Сбор подзадач
        $('.subtask-item').each(function() {
          task.subtasks.push({
            description: $(this).find('.subtask-description').val()
          });
        });

        // Сбор формата ответа
        $('.response-format-item').each(function() {
          task.responseFormat.push({
            content: $(this).find('.response-content').val()
          });
        });

        let data = { task: task };
        
        $.ajax({
          url: "/api/tasks/create-task",
          type: "POST",
          contentType: "application/json",
          dataType: "json",
          data: JSON.stringify(data),

          complete: function() {
            setTimeout(() => {
              eventLogModal("open", "check", "Задание создано!");
            }, 1000)
          }
        });       
      });
    });
  </script>
</body>
</html>