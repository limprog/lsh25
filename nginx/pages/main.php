<?php
session_start();

if (isset($_SESSION["bearerToken"])){
  $session = true;
  $bearerToken = $_SESSION["bearerToken"];
  echo "Сессия активна. $bearerToken";
} else {
  $session = false;
  echo "Сессия неактивна.";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <link rel="stylesheet" href="css/style.css">
  <title>ITMO FireFly</title>
</head>
<body>
  <header> <a href="" id="logo-text">ITMO FireFly</a>
    <div class="header-btn-layout">
      <button class="header-btn" onclick="eventModalSign('open')">Войти</button>
    </div>
  </header>
  <main class="default-main">
    <section class="start-section">
      <div class="start-layout">
        <h1>ITMO FireFly - современная
          <br>и многофункциональная
          <br>платформа разметки</h1>
        <button class="start-btn-login" onclick="eventModalSign('open', 'customer')">
          <div class="text-btn">Войти как заказчик</div>
          <div class="icon-btn">
            <svg viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
              <path d="M14.99 10.01V5.02H5.03V0H24.6V20H19.98V10.01H14.99Z"/>
              <path d="M5.01 19.99C5.01 21.53 5.01 23.06 5.01 24.6H0V19.99H5.01Z"/>
              <path d="M15.01 9.99V15H10V9.99H15.01Z"/>
              <path d="M10.01 14.99V20H5V14.99H10.01Z"/>
            </svg>
          </div>
        </button>
      </div>
      <img src="assets/images/start-bg-image.png" alt="Изображение разметки" id="start-bg-image">
    </section>
    <section class="sheet-section">
      <h2 class="sheet-h"><!--Современный и удобный инструментарий<br>для эффективной организации разметки данных--></h2>
      <div class="advantage-layout">
        <div class="advantage-block">
          <p>Ускорьте разработку AI-моделей с помощью высококачественной и эффективной разметки данных</p>
          <svg viewBox="0 0 64 216" xmlns="http://www.w3.org/2000/svg">
            <path d="M40.7555 216V24.6L0.105469 49.05V24.3L40.7555 -8.58307e-06H63.1055V216H40.7555Z"/>
          </svg>
        </div>
        <div class="advantage-block" style="align-self: end;">
          <p>Повысьте качество данных для обучения AI-моделей благодаря точной разметке и базе знаний</p>
          <svg viewBox="0 0 142 221" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0.90625 220.85L1.05625 200.75L98.7063 112.55C107.106 104.95 112.656 97.75 115.356 90.95C118.156 84.05 119.556 76.85 119.556 69.35C119.556 60.45 117.456 52.4 113.256 45.2C109.056 38 103.406 32.3 96.3063 28.1C89.2063 23.8 81.2563 21.65 72.4563 21.65C63.2563 21.65 55.0563 23.85 47.8563 28.25C40.6563 32.65 34.9563 38.45 30.7563 45.65C26.6563 52.85 24.6563 60.7 24.7563 69.2H2.25625C2.25625 56.1 5.35625 44.35 11.5563 33.95C17.7563 23.55 26.1563 15.4 36.7563 9.49999C47.3563 3.49999 59.3563 0.499996 72.7563 0.499996C85.8563 0.499996 97.6063 3.59999 108.006 9.79998C118.506 15.9 126.756 24.2 132.756 34.7C138.856 45.1 141.906 56.7 141.906 69.5C141.906 78.5 140.806 86.45 138.606 93.35C136.506 100.15 133.156 106.5 128.556 112.4C124.056 118.2 118.306 124.2 111.306 130.4L24.6063 208.7L21.1563 199.7H141.906V220.85H0.90625Z"/>
          </svg>
        </div>
        <div class="advantage-block">
          <p>Оптимизируйте процессы разметки данных для повышения общей эффективности работы команды</p>
          <svg viewBox="0 0 137 221" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M67.2297 220.35C56.5297 220.35 46.6797 218.45 37.6797 214.65C28.6797 210.85 20.9297 205.45 14.4297 198.45C7.92969 191.35 3.17969 182.9 0.179688 173.1L21.1797 166.8C24.7797 177.5 30.6797 185.6 38.8797 191.1C47.1797 196.6 56.5297 199.25 66.9297 199.05C76.5297 198.85 84.7797 196.7 91.6797 192.6C98.6797 188.5 104.03 182.8 107.73 175.5C111.43 168.2 113.28 159.75 113.28 150.15C113.28 135.45 108.98 123.65 100.38 114.75C91.8797 105.75 80.6297 101.25 66.6297 101.25C62.7297 101.25 58.6297 101.8 54.3297 102.9C50.0297 103.9 46.0297 105.35 42.3297 107.25L31.2297 89.7L115.23 12.15L118.83 21.15H8.72969V-8.58307e-06H133.53V21.45L60.3297 91.35L60.0297 82.65C75.0297 80.75 88.2297 82.45 99.6297 87.75C111.03 93.05 119.93 101.15 126.33 112.05C132.83 122.85 136.08 135.55 136.08 150.15C136.08 163.95 133.08 176.15 127.08 186.75C121.18 197.25 113.03 205.5 102.63 211.5C92.2297 217.4 80.4297 220.35 67.2297 220.35Z"/>
          </svg>
        </div>
      </div>
      <svg viewBox="0 0 1619 188" fill="none" xmlns="http://www.w3.org/2000/svg" id="itmo-firefly">
        <path d="M0 183.75V3.75H18.375V183.75H0Z" fill="black"/>
        <path d="M116.225 183.75V21.375H53.3496V3.75H197.475V21.375H134.6V183.75H116.225Z" fill="black"/>
        <path d="M230.02 183.75V3.75H246.895L317.645 155.125L388.02 3.75H405.145V183.625H387.645V44.75L323.52 183.75H311.645L247.645 44.75V183.75H230.02Z" fill="black"/>
        <path d="M525.6 187.5C507.683 187.5 492.558 183.542 480.225 175.625C467.891 167.625 458.558 156.583 452.225 142.5C445.891 128.417 442.725 112.167 442.725 93.75C442.725 75.3333 445.891 59.0833 452.225 45C458.558 30.9167 467.891 19.9167 480.225 12C492.558 4 507.683 0 525.6 0C543.6 0 558.725 4 570.975 12C583.308 19.9167 592.641 30.9167 598.975 45C605.391 59.0833 608.6 75.3333 608.6 93.75C608.6 112.167 605.391 128.417 598.975 142.5C592.641 156.583 583.308 167.625 570.975 175.625C558.725 183.542 543.6 187.5 525.6 187.5ZM525.6 169.875C539.683 169.875 551.433 166.667 560.85 160.25C570.266 153.833 577.308 144.917 581.975 133.5C586.725 122 589.1 108.75 589.1 93.75C589.1 78.75 586.725 65.5417 581.975 54.125C577.308 42.7083 570.266 33.7917 560.85 27.375C551.433 20.9583 539.683 17.7083 525.6 17.625C511.516 17.625 499.808 20.8333 490.475 27.25C481.141 33.6667 474.1 42.625 469.35 54.125C464.683 65.5417 462.308 78.75 462.225 93.75C462.141 108.75 464.433 121.958 469.1 133.375C473.85 144.708 480.933 153.625 490.35 160.125C499.766 166.542 511.516 169.792 525.6 169.875Z" fill="black"/>
        <path d="M708.682 183.75V3.75H812.057V22.125H727.057V84.625H797.057V102.875H727.057V183.75H708.682Z" fill="black"/>
        <path d="M849.658 183.75V3.75H868.033V183.75H849.658Z" fill="black"/>
        <path d="M918.008 183.75V3.75H987.508C989.258 3.75 991.216 3.83333 993.383 3.99999C995.633 4.08333 997.883 4.33333 1000.13 4.75C1009.55 6.16666 1017.51 9.45833 1024.01 14.625C1030.59 19.7083 1035.55 26.125 1038.88 33.875C1042.3 41.625 1044.01 50.2083 1044.01 59.625C1044.01 73.2083 1040.42 85 1033.26 95C1026.09 105 1015.84 111.292 1002.51 113.875L996.133 115.375H936.383V183.75H918.008ZM1027.01 183.75L991.508 110.5L1009.13 103.75L1048.13 183.75H1027.01ZM936.383 97.875H987.008C988.508 97.875 990.258 97.7917 992.258 97.625C994.258 97.4583 996.216 97.1667 998.133 96.75C1004.3 95.4167 1009.34 92.875 1013.26 89.125C1017.26 85.375 1020.22 80.9167 1022.13 75.75C1024.13 70.5833 1025.13 65.2083 1025.13 59.625C1025.13 54.0417 1024.13 48.6667 1022.13 43.5C1020.22 38.25 1017.26 33.75 1013.26 30C1009.34 26.25 1004.3 23.7083 998.133 22.375C996.216 21.9583 994.258 21.7083 992.258 21.625C990.258 21.4583 988.508 21.375 987.008 21.375H936.383V97.875Z" fill="black"/>
        <path d="M1088.22 183.75V3.75H1200.72V21.375H1106.6V83.625H1185.72V101.25H1106.6V166.125H1200.72V183.75H1088.22Z" fill="black"/>
        <path d="M1243.3 183.75V3.75H1346.68V22.125H1261.68V84.625H1331.68V102.875H1261.68V183.75H1243.3Z" fill="black"/>
        <path d="M1384.28 183.75V3.75H1402.65V166.125H1488.15V183.75H1384.28Z" fill="black"/>
        <path d="M1539.94 183.75V108.375L1479.56 3.75H1500.81L1549.19 87.375L1597.31 3.75H1618.56L1558.44 108.375V183.75H1539.94Z" fill="black"/>
      </svg>
      <img src="assets/images/watch-bg-image.png" alt="Image" class="sheet-bg-images watch">
      <img src="assets/images/circle-bg-image.png" alt="Image" class="sheet-bg-images circle">
    </section>
  </main>
  <div class="modal-bg-blur"></div>
  <form class="modal-sign">
    <svg width="34" height="34" viewBox="0 0 34 34" xmlns="http://www.w3.org/2000/svg" id="close-sign" tabindex="3">
      <rect x="24" width="10" height="10" rx="3"/>
      <rect x="12" y="12" width="10" height="10" rx="3"/>
      <rect width="10" height="10" rx="3"/>
      <rect y="24" width="10" height="10" rx="3"/>
      <rect x="24" y="24" width="10" height="10" rx="3"/>
    </svg>
    <!--<div class="switch-layout">
      <button type="button" class="switch-btn active">Исполнитель</button>
      <button type="button" class="switch-btn">Заказчик</button>
    </div>-->
    <div class="sign-inputs-layout">
      <label for="login-input" class="sign-label">Логин</label>
      <input type="text" placeholder="Логин" class="sign-input" id="login-input" required>
      <label for="password-input" class="sign-label">Пароль</label>
      <input type="password" placeholder="Пароль" class="sign-input" id="password-input" required>
    </div>
    <button type="submit" class="sign-btn">
      <p>Вход</p>
      <span class="loader"></span>
    </button>
  </form>
  <div class="log-modal">
    <div class="log-icon">
      <svg viewBox="0 0 16 10" xmlns="http://www.w3.org/2000/svg" id="log-icon-check">
        <path d="M0.692816 6.73273L3.55469 9.43109C4.35946 10.1896 5.66399 10.1896 6.46877 9.43109L15.3072 1.09767C15.5707 0.840362 15.5632 0.430333 15.2903 0.181826C15.0241 -0.0606087 14.602 -0.0606087 14.3358 0.181826L5.49742 8.51524C5.22915 8.76809 4.79431 8.76809 4.52607 8.51524L1.6642 5.81688C1.39129 5.56838 0.956415 5.57551 0.692849 5.83282C0.435723 6.08382 0.435723 6.48173 0.692816 6.73273Z"/>
      </svg>
      <svg viewBox="0 0 10 12" xmlns="http://www.w3.org/2000/svg" id="log-icon-cross">
        <path d="M5.64597 6.00008L9.88679 0.816656C10.0618 0.603159 10.0298 0.288164 9.81579 0.113166C9.6018 -0.0608313 9.28732 -0.0303317 9.11232 0.184165L5 5.21059L0.887178 0.183665C0.711685 -0.0308317 0.397199 -0.0613313 0.183708 0.112666C-0.0302825 0.288164 -0.0617813 0.602659 0.112711 0.816156L4.35403 6.00008L0.113211 11.1835C-0.0617812 11.397 -0.0297826 11.712 0.184208 11.887C0.277204 11.9625 0.388699 12 0.500195 12C0.645188 12 0.788682 11.9375 0.887678 11.8165L5 6.78957L9.11282 11.816C9.21182 11.937 9.35531 11.9995 9.50031 11.9995C9.6118 11.9995 9.7233 11.9625 9.81629 11.8865C10.0303 11.711 10.0618 11.3965 9.88729 11.183L5.64597 6.00008Z"/>
      </svg>
    </div>
    <p class="log-message">Успешный вход!</p>
  </div>
</body>
<script>
const startSect = $(".start-section");
const modalSign = $(".modal-sign");
const modalBgBlur = $(".modal-bg-blur");
const closeSign = $("#close-sign");
//const switchBtn = $(".switch-btn");
const signForm = $(".modal-sign");
const signBtn = $(".sign-btn");
const logModal = $(".log-modal");
const logMessage = $(".log-message");



function eventModalSign(event, type = false){
  if (event == "open"){
    modalBgBlur.addClass("active");
    modalSign.addClass("active");
    if (type == "customer"){

    }
  } else {
    modalBgBlur.removeClass("active");
    modalSign.removeClass("active");
  }
}

function eventLogModal(event, type = "check", text = "Сообщение"){
  if (event == "open"){
    logModal.removeClass("check cross");
    logMessage.text(text)
    if (type == "check"){
      logModal.addClass("check");
    } else {
      logModal.addClass("cross");
    }
    logModal.addClass("active");
    setTimeout(() => {
      logModal.removeClass("active");
    }, 2000);
  } else {
    logModal.removeClass("active");
  }
}



$(window).scroll(() => {
  if ($(window).scrollTop() > 150) {
    startSect.addClass("blur");
  } else {
    startSect.removeClass("blur");
  }
})

closeSign.click(() => {
  eventModalSign("close");
})

/*switchBtn.each((index, btn) => {
  $(btn).click(() => {
    switchBtn.removeClass("active");
    $(btn).addClass("active");
  })
})*/

modalSign.submit(() => {
  event.preventDefault();

  signBtn.prop("disabled", true);

  let data = {
    username: $(modalSign.find("input")[0]).val(),
    password: $(modalSign.find("input")[1]).val()
  }

  $.ajax({
    url: "/api/users/login",
    type: "POST",
    contentType: "application/json",
    dataType: "json",
    data: JSON.stringify(data),

    success: (response) => {
      eventLogModal("open", "check", "Вы успешно вошли!");
      setTimeout(() => {
        location.reload();
      }, 2000);
    },
    error: function(jqXHR, textStatus, errorThrown) {
      if (jqXHR.responseJSON){
        if(jqXHR.status == 400){
          eventLogModal("open", "cross", "Неправильный логин или пароль.");
        }
      } else {
        eventLogModal("open", "cross", "Ошибка на сервере.");
      }
    },
    complete: function() {
      signBtn.prop("disabled", false);
    }
  });
})

logModal.click(() => {
  eventLogModal("close");
})


</script>
</html>