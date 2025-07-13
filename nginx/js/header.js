const modalSign = $(".modal-sign");
const modalBgBlur = $(".modal-bg-blur");
const closeSign = $("#close-sign");
//const switchBtn = $(".switch-btn");
const signForm = $(".modal-sign");
const signBtn = $(".sign-btn");
const logModal = $(".log-modal");
const logMessage = $(".log-message");
const modalMenu = $(".modal-menu");
const menuBtn = $("#menu-btn");
const menuRect = $(".menu-btn-rect");



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

function eventModalMenu(){
  if (!modalMenu.hasClass("active")){
    modalBgBlur.css("z-index", 5);
    modalBgBlur.addClass("active");
    modalMenu.addClass("active");
    menuRect.addClass("active");
  } else {
    modalMenu.removeClass("active");
    menuRect.removeClass("active");
    modalBgBlur.removeClass("active");
    modalBgBlur.css("z-index", 5);
  }
}



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

menuBtn.click(eventModalMenu);