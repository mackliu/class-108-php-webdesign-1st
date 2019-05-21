<?php
/********************************************
 * 登入檢查專用的API
 * 1.檢查欄位是否有輸入
 * 2.檢查帳密是否符合
 * 3.檢查為admin或是一般會員
 * 4.回傳代表值即可(1,2,3,4)
 *    1 => admin登入
 *    2 => 一般會員登入
 *    3 => 帳密錯誤
 *    4 => 欄位空白
 * 5.由於是由前端單獨執行本支程式，因此要記得
 *   先匯入base.php檔，才能使用到資料庫及自訂
 *   函式。
 ********************************************/ 

include_once "base.php";
if(!empty($_POST)){  //判斷是否有POST的值傳入

    $acc=$_POST['acc'];
    $pw=$_POST['pw'];

    //檢查欄位是否有輸入
    if($acc=="" || $pw==""){

      echo 4;  //欄位空白未輸入的話回傳4

    }else{

        //建立查詢帳號密碼的語法
        $sql="select count(*) from user where acc='$acc' && pw='$pw'";
      
        //取得查詢結果的筆數
        $user=$pdo->query($sql)->fetchColumn();

        if($user){ //判斷帳號密碼是否正確

          //建立session變數來存放登入的狀態及使用者的帳號
          $_SESSION['login']=true;
          $_SESSION['user']=$acc;

          if($acc=='admin'){
            
            echo 1;   //帳密符合admin的話回傳1

          }else{

            echo 2;   //帳密符合一般會員的話回傳2
          }
        }else{

          echo 3;   //帳號不符的話回傳3

        }
    }

}

?>