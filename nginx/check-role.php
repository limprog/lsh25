<?php

if (isset($_COOKIE["role"])){
  $userRole = $_COOKIE["role"];
} else {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "http://89.169.146.136:8081/users/role?username=" . $userLogin);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
      "Authorization: Bearer " . $bearerToken
  ]);
  $result = curl_exec($ch);

  if (curl_error($ch)){
    $userRole = false;
  } else {
    $userRole = $result;
  }
}

?>