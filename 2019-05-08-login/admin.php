<?php

//利用自訂的函式來取出所有的使用者
  $users=all("user");

echo "<ul>";

//利用迴圈來取出所有的使用者
foreach ($users as $key => $value) {

  //每個使用者的資料加上連結，並在連結後加上使用者的資料id
  echo "<li><a href='?do=admin&id=".$value['id']."'>".$value['acc']."-".$value['name']."</a></li>";
}
echo "</ul>";

echo "<br><br>";


//檢查是否有$_GET['id']的值存在
if(!empty($_GET['id'])){

  //依照取得的使用者id值從資料庫撈出資料
  $user=find("user",$_GET['id']);

  //在畫面上顯示使用者的資料
  echo "acc=".$user['acc']."<br>";
  echo "name=".$user['name']."<br>";
  echo "permission=".$user['permission']."<br>";
?>
  <!---顯示權限的設定列表--->
  <form action="index.php?do=admin" method="post">
    <input type="checkbox" name="per[]" value="1">關於我們<br>
    <input type="checkbox" name="per[]" value="2">最新資訊<br>
    <input type="checkbox" name="per[]" value="3">活動資訊<br>
    <input type="checkbox" name="per[]" value="4">產品訂購<br>
    <input type="checkbox" name="per[]" value="5">留言板<br>
    <input type="checkbox" name="per[]" value="6">生活留影<br>
    <!---設定一個隱藏欄位用來存放使用者的id--->
    <input type="hidden" name="id" value="<?=$user['id'];?>">
    <input type="submit" value="確認送出">
  </form>
<?php
}

//檢查是否有表單送過來的POST值，為了避免跟其他的功能衝突，同時也檢查使用者ID的值
if(!empty($_POST['per']) && !empty($_POST['id'])){

  $per=serialize($_POST['per']); //將設定權限的值轉成序列化的字串
  $id=$_POST['id'];

  //將更新需要的參數製作成陣列格式
  $array=[
    'table'=>"user",
    'set'=>[
      'permission'=>$per
    ],
    'where'=>[
      'id'=>$id
    ]
  ];
  
  //利用自訂函式來更新資料
  $result=update($array);

  //顯示更新成功或失敗;
  if($result){
    echo "更新成功";
  }else{
    echo "更新失敗";
  }


}

?>
