
<?php
session_start();

if(empty($_SESSION['login'])){ //判斷是否有狀態值或GET值
?>
<table class="login">
  <tr>
    <td>帳號:</td>
    <td><input type="text" name="acc" id="acc" value=""></td>
  </tr>
  <tr>
    <td>密碼:</td>
    <td><input type="password" name="pw" id="pw" value=""></td>
  </tr>
  <tr>
    <td><input type="button" value="確認" onclick="login()"></td>
    <td><input type="reset" value="清除"></td>
  </tr>
</table>
<?php 
}else{
  echo "你已登入過<br>";
  echo "<a href='index.php?do=member'>回到會員中心</a>";
}
?>

