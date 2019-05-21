<?php
/********************************************
 * 產生上方導覽選單資料的專用API
 * 1.以陣列的方式來存選單的內容
 * 2.依照登入與否及帳號特性來區別選單的內容
 * 3.固定會出現的選單內容可以設為預設內容
 * 4.以$menu[]的方式來增加陣列的子元素
 *   或是以函式array_push來增加子元素
 * 5.將陣列編碼成json格式後回傳給前端
 ********************************************/ 

include_once "base.php";   //匯入自訂函式及常用的變數

//建立預設的選單資料陣列，陣列的內容為連結的文字及連結的網址
$menu=[
         ["title"=>"首頁","url"=>"javascript:loadpage('#content','content.php')"],
         ["title"=>"註冊會員","url"=>"javascript:loadpage('#content','reg.php')"],
      ];

//根據登入的狀況將選單分成三種狀況來增加不同的內容(實務上的狀況判斷可能更多)      
if(!empty($_SESSION['login']) && $_SESSION['user']=="admin"){

  //admin登入成功時的選單內容
  $menu[]=["title"=>"管理頁面","url"=>"javascript:loadpage('#content','admin.php')"];
  $menu[]=["title"=>"登出","url"=>"javascript:logout()"];
  
}else if(!empty($_SESSION['login'])){
  
  //一般會員登入成功時的選單內容
  $menu[]=["title"=>"登出","url"=>"javascript:logout()"];

}else{

  //未登入時的選單內容
  $menu[]=["title"=>"登入","url"=>"javascript:loadpage('#content','login.php')"];
  
}

//編碼成json格式並回傳
echo json_encode($menu);

?>
