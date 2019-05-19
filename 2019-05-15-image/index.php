<?php
include "fun.php";  //匯入常用的變數及自訂函式
session_start();
//宣告一個變數來儲存錯誤訊息
$err="";

//判斷有沒有表單的資料傳送過來
if(!empty($_POST)){

  //判斷驗證碼是否正確
  if($_POST['code']==$_SESSION['code']){

    //判斷檔案是否上傳正確
    if(!empty($_FILES['pic']['tmp_name'])){

      if(chkpic($_FILES['pic']['type'])){
        //取得檔名陣列
        $filename=setpicname($_FILES['pic']['type']);

        //移動檔案到指定目錄下,並改命為$filename
        move_uploaded_file($_FILES['pic']['tmp_name'],"./img/$filename[1]");

        //呼叫縮圖處理函式來產生縮圖
        thumbnail("./img/$filename[1]" , $_FILES["pic"]["type"] , $filename[0] , 0);

        //取得檔案的描述文字
        $desc=$_POST['desc'];

        //將檔案資訊存入資料表
        $sql="insert into img (`name`,`path`,`description`) values('$filename[0]','./img/$filename[1]','$desc')";
        $pdo->query($sql);

      }else{

        //檔案類型不是指定的圖檔，回傳錯誤訊息
        $err="請確認上傳的檔案為圖片檔案";
      }

    }
  }else{
    $err="驗證碼錯誤，請重新輸入";
  }
}

//產生一個隨機驗證碼並存入SESSION或COOKIE
$_SESSION['code']=code(5);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PHP圖形處理課程綜合應用</title>
  <style>
    h1,
    h3 {
      text-align: center;
    }

    h3 {
      color: red;
    }

    form,
    table {
      border-collapse: collapse;
      padding: 10px;
      margin: 20px auto 0 auto;
      border: 2px solid #ccc;
      width: 600px;
    }

    td {
      text-align: center;
      padding: 5px;
      border: 1px solid #ccc;
    }
  </style>
</head>

<body>
  <h1>相簿</h1>
  <h3><?=$err;?></h3>
  <form action="?" method="post" enctype="multipart/form-data">
    <p>上傳圖片：<input type="file" name="pic"></p>
    <p>圖片說明：<input type="text" name="desc"></p>
    <p>驗證碼：<input type="text" name="code"></p>
    <p><!----利用產生的隨機碼來產生驗證碼圖形---->
      <img src="<?=codepic($_SESSION['code'],16,0);?>">
      <!-----利用javascript來重整頁面以重新產生驗證碼------>
      <input type="button" value="重新產生" onclick="javascript:location.href='index.php'"></p>
    <p><input type="submit" value="上傳"></p>
  </form>
  <table>
    <tr>
      <td>縮圖</td>
      <td>檔名</td>
      <td>路徑</td>
      <td>描述</td>
    </tr>
    <?php

//列出資料表中的檔案列表,並顯示縮圖檔
$sql="select * from img";
$rows=$pdo->query($sql)->fetchAll();
foreach($rows as $r){
?>
    <tr>
      <td><a href="<?=$r['path'];?>"><img src='./thumb/<?=$r['name'].".png";?>'></a></td>
      <td><?=$r['name'];?></td>
      <td><?=$r['path'];?></td>
      <td><?=$r['description'];?></td>
    </tr>
    <?php
}
?>
  </table>
</body>

</html>