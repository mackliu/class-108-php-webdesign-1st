<?php
session_start();

//清空並刪除session的內容以登出使用者
$_SESSION['login']=null;
$_SESSION['user']=null;
unset($_SESSION['login']);
unset($_SESSION['user']);


?>