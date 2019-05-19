<?php

  //檢查是否有透過POST傳遞過來的值
  if(!empty($_POST)){

   //將POST過的值存入相應的變數
    $acc=$_POST['acc'];
    $pw=$_POST['pw'];
    $name=$_POST['name'];

  /********利用自訂函式檢查帳號並取得錯誤訊息************/

    $accErr=chkform(['space','length','sym'],$acc);

  /********利用自訂函式檢查密碼並取得錯誤訊息************/

    $pwErr=chkform(['space','length','num','eng','sym'],$pw);

  /********利用自訂函式檢查名稱並取得錯誤訊息***********/

    $nameErr=chkform(['space','sym'],$name);


  /*******檢查帳號是否已存在 */

  $sql="select acc from user where acc='$acc'";
  $chkAcc=$pdo->query($sql)->fetch();
  if($chkAcc){
    $chkAccount=true;
    echo "帳號重覆";
  }else{
    $chkAccount=false;
  }

  /*** 根據以上的檢查結果來決定要不要新增資料 ****/

  if($accErr=="" && $pwErr=="" && $nameErr=="" && $chkAccount==false){

    //建立一個設的權限陣列，每個新增的會員都會有這個預設的權限
    $pr=serialize([1]);

     //建立新增資料的語法
     $sql="insert into user (`acc`,`pw`,`name`,`permission`) values('$acc','$pw','$name',`$pr`)";
    
     //送出新增語法
     $res=$pdo->query($sql);
       if($res){
         echo "新增成功";
       }else{
         echo "新增異常";
       }
    }
}
?>

  <style>
    .errmeg{
      color:red;
      font-size:12px;
      font-family:"微軟正黑體";
      text-align:left;
    }
  </style>
  <form action="index.php?do=reg" method="post">
  <table>
    <tr>
      <td>帳號</td>
      <td>
        <input type="text" name="acc" id="acc">
        <p class="errmeg">
        <?php
        
          //如果錯誤訊息的字串不是空值，就顯示出來
          if(!empty($accErr)){
            echo $accErr;
          }
        ?>
        </p>

      </td>
    </tr>
    <tr>
      <td>密碼</td>
      <td><input type="password" name="pw" id="pw">
      <p class="errmeg">
        <?php
          if(!empty($pwErr)){
            echo $pwErr;
          }
        ?>
        </p>
        </td>      
    </tr>
    <tr>
      <td>本名

      </td>
      <td>
        <input type="text" name="name" id="name">
        <p class="errmeg">
          <?php
            if(!empty($nameErr)){
              echo $nameErr;
            }
          ?>
          </p>
      </td>
    </tr>
    <tr>
      <td><input type="submit" value="新增"></td>
      <td><input type="reset" value="重置"></td>
    </tr>
  </table>
</form>

