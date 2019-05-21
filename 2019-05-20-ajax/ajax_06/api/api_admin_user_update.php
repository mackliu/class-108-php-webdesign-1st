<?php

include_once "base.php";

//檢查是否有表單送過來的POST值，為了避免跟其他的功能衝突，同時也檢查使用者ID的值
if(!empty($_POST['pr']) && !empty($_POST['id'])){
  $pr=serialize($_POST['pr']); //將設定權限的值轉成序列化的字串
  $id=$_POST['id'];

  //將更新需要的參數製作成陣列格式
  $array=[
    'table'=>"user",
    'set'=>[
      'permission'=>$pr
    ],
    'where'=>[
      'id'=>$id
    ]
  ];
  
  //利用自訂函式來更新資料
  $result=update($array);

  //顯示更新成功或失敗;
  if($result){
    echo 1;
  }else{
    echo 0;
  }


}