<?php
$dsn="mysql:host=localhost;charset=utf8;dbname=108_php_01";
$pdo=new PDO($dsn,"root","");

/******************************************************************
 * 
 * 根據檔案類型來回傳檔名及完整檔案名稱
 * 
 * ****************************************************************/

function setpicname($type){

  //利用code函式產生一組7字元的隨機碼當成檔名
  $name=code(7);
  switch($_FILES["pic"]["type"]){
    case "image/gif":
      $sub=".gif";
    break;
    case "image/jpeg":
    case "image/jpg":
      $sub=".jpg";
    break;
    case "image/png" :
      $sub=".png";
    break;
    case "image/bmp":
      $sub=".bmp";
    break;
  }

  return [$name,$name.$sub];
}


/******************************************************************
 * 
 * 檢查檔案類型是否為網頁可用圖檔
 * 
 * ****************************************************************/
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

/******************************************************************
 * 
 * 縮圖產生自訂函式
 * 
 * ****************************************************************/
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
  $white=imagecolorallocate($des,255,255,255);
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


/******************************************************************
 * 
 * 圖形驗證碼產生函式
 * 
 * ****************************************************************/
function codepic($code,$fontsize,$font){
  $fontlist=['times.ttf','timesbd.ttf','timesbi.ttf','timesi.ttf'];
  //設定字形檔的路徑(需要絕點路徑)
  $fontpath=realpath("./font/$fontlist[$font]");

  //先計算出字形字串佔用的區域坐標
  $text_box=imagettfbbox($fontsize,0,$fontpath,$code);
  $img_w=$text_box[2]+strlen($code)*10; //利用坐標來算出圖片的寬度並加上間距
  $img_h=$text_box[7]*-1+35;  //利用坐標來算出圖片的高度並加上亂數Y坐標的範圍15及上下邊距20

  //建立一個用來存放驗證碼的圖片資源,全彩
  $img=imagecreatetruecolor($img_w,$img_h);
  
  //設定一個背景色
  $bg=imagecolorallocate($img,255,200,255);
   
  //填上背景色
  imagefill($img,0,0,$bg);

  //先在底圖上畫線條
  $lines=rand(2,5);   //亂數決定線條數

  for($i=0;$i<$lines;$i++){

   //根據函式需要來產生需要的坐標資訊及色彩
     $start_x=rand(5,intval($img_w*0.25));  //在圖片0~1/4的範圍內產生一個x坐標點
     $start_y=rand(10,$img_h-10);           //在驗證碼的高度範圍內產生一個y坐標點
     $end_x=rand(intval($img_w*0.75+5),$img_w-5);    //在圖片3/4~1的範圍內產生一個x坐標點
     $end_y=rand(10,$img_h-10);                      //在驗證碼的高度範圍內產生一個y坐標點

     //用亂數產色一個色彩
     $line_color=imagecolorallocate($img,rand(50,200),rand(50,200),rand(50,200));

     //執行畫線函式
     imageline($img,$start_x,$start_y,$end_x,$end_y,$line_color);

  }

  //在底圖上畫文字
  $str_x=5; //預設從底圖左側5點的地方開始畫
  $str_y=0; 
  for($i=0;$i<strlen($code);$i++){

    $color=imagecolorallocate($img,rand(50,200),rand(50,200),rand(50,200));

     //內建字形的畫字函式->imagestring($img,5,$str_x,$str_y,substr($code,$i,1),$color);

     // imagettfbbox() -> image true type font bounding box -用來取得字形的四點坐標資訊(左下,右下,右上,左上);
     //取得毎個字元的四角坐標值
     $textbox=imagettfbbox($fontsize,0,$fontpath,substr($code,$i,1));
     
     //計算字元在Y軸的位置(上邊距+亂數範圍+字形高度)
     $str_y=10+rand(0,15)+$textbox[7]*-1;
     
     //用亂數產生一個-30~30的傾斜角度
     $angle=rand(-30,30);

     //將單一字元畫在底圖上
     imagettftext($img,$fontsize,$angle,$str_x,$str_y,$color,$fontpath,substr($code,$i,1));
     
     //計算下一個字元在X軸的位置(將上一個字元的x坐標加上字元的寬度),再加上10px的字元間距
     $str_x=$str_x+$textbox[2]+10;
  }

  //儲存成png檔案
  //imagepng($img,"./code/login_code.png");

  //轉成BASE64後傳到客戶端
  ob_start();           //建立一個緩衝區
  imagepng($img);        //將圖片資料寫入緩衝區
  $output = "data:image/png;base64,".base64_encode(ob_get_clean()); //將緩衝區中的資料以base64_encode()的方式做編碼
  imagedestroy($img);    //刪除記憶體中的圖片資料

  return $output; 

}


/******************************************************************
 * 
 * 隨機驗證碼產生函式
 * 
 * ****************************************************************/
function code($x){

 //宣告一個空字串變數
  $code="";

  //利用迴圈來累加字串
  for($i=0;$i<$x;$i++){

   //宣告一個亂數變數來決定每次迴圈要產生的是數字還是大小寫英文字
     $type=rand(1,3);
     switch($type){
        case "1":
           //產生亂數的數字
           $code=$code . rand(0,9);
        break;
        case "2":
           //產生亂數的小寫英文
           $code=$code . chr(rand(97,122));
        break;
        case "3":
           //產生亂數的大寫英文
           $code=$code . chr(rand(65,90));
        break;
     }
  }

  //回傳最後串完的字串
  return $code;
}

?>