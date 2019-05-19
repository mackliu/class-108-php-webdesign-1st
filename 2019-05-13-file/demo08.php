<?php

/************************************************** 
1.enctype=multipart/form-data
2.input type="file"
3.檔案以二進位方式傳輸到暫存目錄中
4.以$_FILES來存取相關的屬性
  ->$_FILES["file"]["name"] 上傳檔案的原始名稱
  ->$_FILES["file"]["type"] 上傳檔案的檔案類型
    =>"image/gif"
    =>"image/jpeg"
    =>"image/jpg"
    =>"image/png"
  ->$_FILES["file"]["size"] 上傳檔案的原始大小
  ->$_FILES["file"]["tmp_name"] 上傳檔案的暫存位置
  ->$_FILES["file"]["error"] 錯誤代碼
5.move_uploaded_file(source,destination) 移動檔案
6.copy(source,destination) 複製檔案
7.unlink(source) 刪除檔案
***************************************************/

//練習 上傳檔案後提供下載，檔案路徑及資訊存在資料表中
$dsn="mysql:host=localhost;charset=utf8;dbname=108_php_01";
$pdo=new PDO($dsn,"root","");

//利用暫存路徑來判斷是否有上傳檔案
if(!empty($_FILES["file"]["tmp_name"])){

  $name=$_FILES['file']['name'];       //取得檔名
  $type=$_FILES['file']['type'];       //取得檔案類型
  $path=$_FILES["file"]["tmp_name"];   //取得暫存路徑
  $desc=$_POST['desc'];                //取得輸入文字

  //移動檔案到指定目錄下,並改命為$name
  move_uploaded_file($path , "./upload/" . $name);

  //建立SQL語法，並將檔案資訊寫入資料表
  $sql="insert into upload (`name`,`type`,`path`,`description`) values('$name','$type','./upload/$name','$desc')";
  $pdo->query($sql);
  
}

?>
<style>
table{
  border-collapse:collapse;
}
td{
  text-align:center;
  padding:5px;
  border:1px solid #ccc;
}
</style>
<form action="?" method="post" enctype="multipart/form-data">
  選擇上傳檔案：<input type="file" name="file"><br>
  檔案描述：<input type="text" name="desc">
  <input type="submit" value="上傳">
</form>
<table>
  <tr>
    <td>縮圖</td>
    <td>檔名</td>
    <td>類型</td>
    <td>路徑</td>
    <td>描述</td>
    <td>下載</td>
  </tr>
<?php

//列出資料表中的檔案列表,並顯示縮圖檔
$sql="select * from upload";
$rows=$pdo->query($sql)->fetchAll();
foreach($rows as $r){
  //判斷如果檔案不是圖檔，則顯示的縮圖為文件專用的縮圖
  $img=['image/png','image/gif','image/jpg','image/jpeg'];
  if(in_array($r['type'],$img)){
    $icon=$r['path'];
  }else{
    $icon="./upload/doc.png";
  }
?>  
  <tr>
    <td><img src='<?=$icon;?>' style="width:100px;height:100px;"></td>
    <td><?=$r['name'];?></td>
    <td><?=$r['type'];?></td>
    <td><?=$r['path'];?></td>
    <td><?=$r['description'];?></td>
    <td><a href="<?=$r['path'];?>" download><button>下載</button></a></td>
  </tr>
<?php
}
?>
</table>