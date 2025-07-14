<?php 
session_start();

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://89.169.146.136:8081/users/role?username=" . $_COOKIE["userLogin"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer " . $_COOKIE["bearerToken"]
]);
$result = curl_exec($ch);

if (curl_error($ch)){
  $result = false;
} else {
  echo $result;
}
?>