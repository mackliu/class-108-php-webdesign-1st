<?php
/********************************************
 * 註冊會員專用的API
 * 1.檢查各個欄位是否符合規定
 * 2.錯誤訊息使用陣列來儲存
 * 3.完成檢查後將錯誤訊息以json格式回傳
 * 4.由於是由前端單獨執行本支程式，因此要記得
 *   先匯入base.php檔，才能使用到資料庫及自訂
 *   函式。
 ********************************************/ 

  include_once "base.php";
  
  //建立一個空陣列，用來存放各項檢查後的結果
  $meg=[];

  if(!empty($_POST)){

   //將POST過來的值存入相應的變數
    $acc=$_POST['acc'];
    $pw=$_POST['pw'];
    $name=$_POST['name'];

  /********利用自訂函式檢查帳號並取得錯誤訊息************/

    $accErr=chkform(['space','length','sym'],$acc);
    
  /********利用自訂函式檢查密碼並取得錯誤訊息************/

    $pwErr=chkform(['space','length','num','eng','sym'],$pw);

  /********利用自訂函式檢查名稱並取得錯誤訊息***********/

    $nameErr=chkform(['space','length'],$name);

  //檢查帳號是否已存在
  $sql="select acc from user where acc='$acc'";
  $chkAcc=$pdo->query($sql)->fetch();

  if($chkAcc){
    $chkAccount=true;

    //如果帳號重覆，則在訊息陣列中存入一筆資料
    $meg["chkAcc"]="帳號重覆";

  }else{

    $chkAccount=false;

  }

  /*** 根據以上的檢查結果來決定要不要新增資料 ****/
  if($accErr=="" && $pwErr=="" && $nameErr=="" && $chkAccount==false){

    //建立一個預設的權限陣列，每個新增的會員都會有這個預設的權限
    $pr=serialize([1,2,3]);

     //建立新增資料的語法
     $sql="insert into user (`acc`,`pw`,`name`,`permission`) values('$acc','$pw','$name','$pr')";

     //送出新增語法
     $res=$pdo->query($sql);

      //依照資料新增的狀況，在訊息陣列中存入一筆資料
       if($res){
                 
         $meg["status"]="新增成功";

       }else{

         $meg["status"]="新增異常";   //資料表有錯誤或SQL語法有錯或欄位資料不符合

       }
    }else{

      //將三個欄位的檢查結果存入訊息陣列中的err元素中
      $meg["err"]=[
                    "acc"=>$accErr,
                    "pw"=>$pwErr,
                    "name"=>$nameErr
                  ];

    }

    //以json的格式編碼陣列並回傳給前端
    echo json_encode($meg);
    
}
?>