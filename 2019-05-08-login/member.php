<?php
  
//判斷是否為登入成功的狀態
  if(empty($_SESSION['login'])){

    //將使用者導向首頁
    header("location:index.php");

    //停止此頁面之後的程式執行
    exit();
  }
?>

 登入成功<br>
 <?php
  //利用登入成功後存在session中的user資料來取得使用者的名稱資料
  $sql="select name from user where acc='". $_SESSION['user'] ."'";
  $name=$pdo->query($sql)->fetchColumn();
  echo "歡迎 ".$name." 光臨";
 ?>
