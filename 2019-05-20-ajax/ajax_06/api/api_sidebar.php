<?php include_once "base.php";?>
<?php

//建立一個選單的資料陣列
$menu=[
        1=>["title"=>"關於我們","url"=>"aboutus.html"],
        2=>["title"=>"最新消息","url"=>"news.html"],
        3=>["title"=>"活動資訊","url"=>"event.html"],
        4=>["title"=>"產品訂購","url"=>"production.html"],
        5=>["title"=>"留言板","url"=>"message.html"],
        6=>["title"=>"生活留影","url"=>"photo.html"]
      ];


//利用session內的使用者帳號來取出使用者的資料
if(!empty($_SESSION['user'])){
  
  $sql="select * from user where acc='".$_SESSION['user']."'";
  $user=$pdo->query($sql)->fetch();
    
  //將權限還原成為陣列
  $pr=unserialize($user['permission']);
}else{

  //預設未登入時只可以看到一個選單項目
  $pr=[1];

}

//建立一個陣列來儲存前端實際能用的選單內容
$prmenu=[];

//以迴圈的方式來將選單內容放入prmenu
foreach($pr as $p){
  $prmenu[]=$menu[$p];
}

//回傳json格式的選單內容
echo json_encode($prmenu);

?>
