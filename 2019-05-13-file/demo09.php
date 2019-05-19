<?php
/*****************************************
 * 建立一個頁面供使用者查詢資料，
 * 並可將查詢完成的資料下載
 *****************************************/

?>
<style>
ul{
  list-style-type:none;
  width:1030px;
  display:flex;
  margin:0;
  padding:0;
}
li{
  border:1px solid #ccc;
  width:350px;
  padding:3px;
  word-wrap:break-word;
}
li:nth-child(1),
li:nth-child(4){
  width:70px;
}

</style>
<!--建立表單來選擇不同年份的資料---->
<form action="?" method="post">
  <select name="year">
    <option value="105年">105年</option>
    <option value="104年">104年</option>
    <option value="103年">103年</option>
    <option value="102年">102年</option>

</select>
<input type="submit" value="送出">

</form>
<?php

//根據POST的值來決定要撈取的資料年份
if(!empty($_POST['year'])){
  $year=$_POST['year'];
}else{
  //預設會顯示105年的資料
  $year="105年";
}

$dsn="mysql:host=localhost;charset=utf8;dbname=108_php_01";
$pdo=new PDO($dsn,"root","");

//建立SQL語法來撈取指定年份的資料
$sql="select * from labor where year='$year'";
$rows=$pdo->query($sql)->fetchAll();

//建立一個依照年份建立的檔案
$file=fopen("labor".$year.".csv","w");

//寫入BOM檔頭
$str=chr(0xEF).chr(0xBB).chr(0xBF);

//利用迴圈來取出每一筆資料並在網頁上顯示
foreach($rows as $r){
  echo "<ul>";
  echo "<li>".$r['year']."</li>";
  echo "<li>".$r['name']."</li>";
  echo "<li>".$r['description']."</li>";
  echo "<li>".$r['duration']."</li>";
  echo "<li>".$r['url']."</li>";
  echo "</ul>";

  //將每一筆資料串成要寫入檔案的字串格式
  $str=$str . $r['year'] .",".$r['name'].",".$r['description'].",".$r['duration'].",".$r['url']."\r\n";
}

//寫入檔案並關閉檔案
fwrite($file,$str);
fclose($file);

?>
<!---建立下載檔案的連結---->
<p><a href="labor<?=$year;?>.csv" download>下載<?=$year;?>度勞工調查資料</a></p>