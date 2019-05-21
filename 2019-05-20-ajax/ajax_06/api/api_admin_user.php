<?php
include_once "base.php";

//檢查是否有$_GET['id']的值存在
if(!empty($_GET['id'])){

  //依照取得的使用者id值從資料庫撈出資料
  $user=find("user",$_GET['id']);

  //將原本字串格式的權限欄位轉成陣列格式
  $user['permission']=unserialize($user['permission']);

  //以json編碼格式回傳
  echo json_encode($user);
  
}
?>