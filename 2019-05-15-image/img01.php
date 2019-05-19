<?php
/****************圖片/檔案上傳************************** 
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

//練習 上傳檔案後提供下載，檔案路徑存在資料表中
$dsn="mysql:host=localhost;charset=utf8;dbname=108_php_01";
$pdo=new PDO($dsn,"root","");

$err=""; //宣告一個變數來儲存錯誤訊息

//利用暫存路徑來判斷是否有上傳檔案
if(!empty($_FILES["pic"]["tmp_name"])){
  //判斷檔案是否屬於網頁可用圖檔
  if(chkpic($_FILES["pic"]["type"])){
    
    //練習:建立新的檔案檔名
    $time=date("Ymdhis"); //取得時間字串

    //製作新的檔案名稱
    /*****************************************************************
     * 利用explode()函式來組合圖檔副檔名
     * $filename=$time . "." . explode(".",$_FILES['pic']['name'])[1];
     * ***************************************************************/

    //利用檔案形態來決定副檔名
    switch($_FILES["pic"]["type"]){
      case "image/gif":
        $filename=$time . ".gif";
      break;
      case "image/jpeg":
      case "image/jpg":
        $filename=$time . ".jpg";
      break;
      case "image/png" :
        $filename=$time . ".png";
      break;
      case "image/bmp":
        $filename=$time . ".bmp";
      break;
    }

    //取得檔案上傳的暫存路徑
    $path=$_FILES["pic"]["tmp_name"];

    //取得檔案的描述文字
    $desc=$_POST['desc'];
    
    //移動檔案到指定目錄下,並改命為$filename
    move_uploaded_file($path,"./img/$filename");
  
    //呼叫縮圖處理函式來產生縮圖
    thumbnail("./img/$filename" ,$_FILES["pic"]["type"], $time);

    //將檔案資訊存入資料表
    $sql="insert into img (`name`,`path`,`description`) values('$time','./img/$filename','$desc')";
    $pdo->query($sql);

  }else{
    
    //檔案類型不符，回傳錯誤訊息
    $err="請確認上傳的檔案為圖片檔案";
  }

}

?>
<style>
h1,h3{
  text-align:center;
}
h3{
  color:red;
}
form ,table{
  border-collapse:collapse;
  padding:10px;
  margin:20px auto 0 auto;
  border:2px solid #ccc;
  width:600px;
}
td{
  text-align:center;
  padding:5px;
  border:1px solid #ccc;
}
</style>
<h1>相簿</h1>
<h3><?=$err;?></h3>
<form action="?" method="post" enctype="multipart/form-data">
  <p>上傳圖片：<input type="file" name="pic"></p>
  <p>圖片說明：<input type="text" name="desc"></p>
  <p><input type="submit" value="上傳"></p>
</form>
<table>
  <tr>
    <td>縮圖</td>
    <td>檔名</td>
    <td>路徑</td>
    <td>描述</td>
  </tr>
<?php

//列出資料表中的檔案列表,並顯示縮圖檔
$sql="select * from img";
$rows=$pdo->query($sql)->fetchAll();
foreach($rows as $r){
?>  
  <tr>
    <td><a href="<?=$r['path'];?>"><img src='./thumb/<?=$r['name'].".png";?>'></a></td>
    <td><?=$r['name'];?></td>
    <td><?=$r['path'];?></td>
    <td><?=$r['description'];?></td>
  </tr>
<?php
}
?>
</table>

<?php

//自訂函式:檢查檔案類型是否為網頁可用圖檔
function chkpic($type){

  //檔案類型的陣列
  $imgs=[
    "image/gif",
    "image/jpeg",
    "image/jpg",
    "image/png",
  ];

  //用in_array()函式來檢查傳入的檔案型態是否在陣列中
  if(in_array($type,$imgs)){
     return true;
  }else{
    return false;
  }
}


/********************************圖形處理*****************************
* imagecreatefrompng(source) 指定建立的圖檔類型，同型的函式有gif,jpeg 
* imagesx(image),imagesy(image) 取得寬高                             
* imagecreatetruecolor(x,y) 建立全彩圖形資源                         
* imagecopyresampled(des,source,dx,fy,dx,dy,fw,fh,dw,dy);           
* imagecopyresize(des,source,fx,fy,dx,dy,fw,fh,dw,dy);           
*    縮放圖形到目的圖形資源中                                       
* imagejpeg(image,path)將圖形資源存成jpeg，同型的函式有gif,png     
* imagedestroy(image)刪除圖形資源                                   
********************************************************************/

//練習:建立一個自訂函式來產生縮圖檔案並放入指定目錄

function thumbnail($path,$filetype,$name){

  //根據檔案型態來決定要用什麼方式開啟來源檔
    /*****************************************************************
     * 利用explode()函式來判斷圖檔副檔名
     * $imagetype=explode(".",$path);
     * $imagetype[0] -> 檔名
     * $imagetype[1] -> 副檔名
     * 如果檔名有多個"."則此方法不適用來找副檔名及判斷檔案格式
     * ***************************************************************/
    
  switch($filetype){
    case "image/png":
      $src=imagecreatefrompng($path);
    break;
    case "image/gif":
      $src=imagecreatefromgif($path);
    break;
    case "image/jpg":
    case "image/jpeg":
      $src=imagecreatefromjpeg($path);
    break;
    case "image/bmp":
      $src=imagecreatefrombmp($path);
    break;
  }

  //取得來源圖片的寬高
  $src_w=imagesx($src);
  $src_h=imagesy($src);

  //根據長寬比例(橫/直)來決定縮放後的長寬
  if($src_w>$src_h){
    $des_w=130;
    $des_h=intval((130/$src_w)*$src_h);
  }else{
    $des_w=intval((130/$src_h)*$src_w);
    $des_h=130;
  } 
  
  //建立一個目標圖片資源(全彩)
  $des=imagecreatetruecolor(150,150);

  //建立白色背景色
  $white=imagecolorallocate($des,255,100,255);
  imagefill($des,0,0,$white);  //將顏色填回目標圖片資源

  //計算縮放後的圖片要畫在目標圖片的起始坐標
  $des_x=intval((150-$des_w)/2);
  $des_y=intval((150-$des_h)/2);

  //將相關參數放入縮放用的函式來產生縮圖
  imagecopyresampled($des,$src,$des_x,$des_y,0,0,$des_w,$des_h,$src_w,$src_h);

  //將完成的圖片資源存成圖檔
  imagepng($des,"./thumb/".$name.".png");

  //刪除記憶體中的圖片資源
  imagedestroy($des);
  imagedestroy($src);
}


?>