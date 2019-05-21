<?php

include_once "base.php";
//利用自訂的函式來取出所有的使用者
  $users=all("user");

//以json格式回傳使用者列表資料
  echo json_encode($users);

?>