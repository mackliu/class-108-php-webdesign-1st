<?php
  //連線資料庫
  $dsn="mysql:host=localhost;charset=utf8;dbname=108_php_01";
  $pdo=new PDO($dsn,"root","");

  session_start();

//建立一個錯誤訊息的字串陣列
 $errMeg=[
      1=>"欄位請勿空白",
      2=>"欄位長度請在4-12之間",
      3=>"欄位全是數字，請至少一個以上的英文字",
      4=>"欄位全是英文，請至少一個以上的數字",
      5=>"欄位請勿使用英數字以外的符號"
    ];

/********************************************************************
 * 
 * 表單檢查的共用函式，依照陣列內容來進行表單內容的檢查
 * 
 * ******************************************************************/
function chkform($array,$str){
  global $errMeg;   
  $err="";

  //以迴圈的方式針對要檢查的項目逐一檢查
  foreach($array as $a){
    switch($a){
      case "space":
        if(chkSpace($str)){
          $err=$err . $errMeg[1];
        }
      break;
      case "length":
      if(!chkLength($str)){
        $err=$err . $errMeg[2];
      }
      break;
      case "num":
      if(chkNum($str)){
        $err=$err . $errMeg[3];
      }
      break;
      case "eng":
      if(chkEng($str)){
        $err=$err . $errMeg[4];
      }
      break;
      case "sym":
      if(chkSym($str)){
        $err=$err . $errMeg[5];
      }
      break;
    }
  }

  //回傳錯誤訊息
  return $err;
}




/********************************************************************
 * 
 *取出指定ID的資料
 * 
 ********************************************************************/

function find($table,$id){
  global $pdo;

  $sql="select * from $table where id='$id'";
  $rows=$pdo->query($sql)->fetch();
  return $rows;
}



/********************************************************************
 * 
 *取出全部資料表的資料
 * 
 ********************************************************************/

function all($table){
  global $pdo;
  $sql="select * from $table";
  $rows=$pdo->query($sql)->fetchAll();
  return $rows;
}



/********************************************************************
 * 
 *更新資料表
 * 
 ********************************************************************/

function update($array){
  global $pdo;
  //處理$set陣列
  foreach($array['set'] as $key => $value){

    $s[]=sprintf("%s='%s'",$key,$value);

  }
  
  //處理$where陣列
  foreach($array['where'] as $key => $value){

    $w[]=sprintf("%s='%s'",$key,$value);

  }
  
  //把陣列內容重組成一個完整的SQL語句
  $sql="update ".$array['table']." set ".implode(',',$s)." where ".implode(" && ",$w)."";

  $result=$pdo->exec($sql); //改用exec來回傳受影響的資料筆數
  return $result;
}


/********************************************************************
 * 
 *檢查字串是否空白
 * 
 ********************************************************************/

function chkSpace($str){
  if($str==""){
    return true;
  }
}



/********************************************************************
 * 
 *檢查字串長度是否符合規定
 * 
 ********************************************************************/

function chkLength($str){
  $min=4;
  $max=12;
  if(strlen($str) >= $min && strlen($str) <= $max){
    return true;
  }
}


/********************************************************************
 * 
 *檢查字串是否全是數字
 * 
 ********************************************************************/

  function chkNum($str){
    $chkNum=0;
    for($i=0;$i<strlen($str);$i++){
  
      $part=substr($str,$i,1);   //依照$i的值逐一取出單一字元

      //判斷利用ord()函式來判斷$part的值是否在數字的ascii範圍內
      if(ord($part) >= 48 && ord($part) <= 57){
        $chkNum++;
      }
    }
    //如果檢查的結果，$chkNum的值和$str的長度一樣，表示整個字串都是數字，回傳true值
    if($chkNum==strlen($str)){
      return true;
    }

  }


/********************************************************************
 * 
 *檢查字串是否全是英文
 * 
 ********************************************************************/  

  function chkEng($str){
    $chkEng=0;
    for($i=0;$i<strlen($str);$i++){
  
      //依照$i的值逐一取出單一字元
      $part=substr($str,$i,1); 

      //判斷利用ord()函式來判斷$part的值是否在英文的ascii範圍內
      if((ord( $part) >= 65 && ord( $part) <= 90) || (ord( $part) >= 97 && ord($part) <= 122) ){
        $chkEng++;
      }
    }

    //如果檢查的結果，$chkEng的值和$str的長度一樣，表示整個字串都是英文，回傳true值
    if($chkEng==strlen($str)){
      return true;
    }
  }


/********************************************************************
 * 
 *檢查字串是否含有英數字以外的字元
 * 
 ********************************************************************/  

  function chkSym($str){
    $chkSym=0;
    for($i=0;$i<strlen($str);$i++){

      $part=substr($str,$i,1);//依照$i的值逐一取出單一字元

      //判斷利用ord()函式來判斷$part的值是否在英文及數字的ascii範圍外
      if(!(ord($part) >= 65 && ord($part) <= 90) && !(ord($part) >= 97 && ord($part) <= 122) && !(ord($part) >= 48 && ord($part) <= 57) ){
        $chkSym++;
      }
  
    }

    //如果檢查的結果，$chkSym的值大於0，表示字串內容含有至少一個特殊符號，回傳true值
    if($chkSym>0){
      return true;
    }
  }
?>