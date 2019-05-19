<?php
/***************************************
 imagecreatefrompng(source) 指定建立的圖檔類型，同型的函式有gif,jpeg
 imagesx(image),imagesy(image) 取得寬高
 imagecreatetruecolor(x,y) 建立全彩圖形資源
 imagecopyresampled(des,source,fx,fy,dx,dy,fw,fh,dw,dy);
    縮放圖形到目的圖形資源中
 imagestring() 將文字畫在圖形上    
 imagettftext() 將ttf字型文字畫在圖形上
 imagejpeg(image,path) 將圖形資源存成jpeg，同型的函式有gif,png
 imagedestroy(image) 刪除圖形資源
 ***************************************/
//練習:製作一個圖形驗證碼機制

//呼叫code()函式來產生一組驗證碼字串
$code=code(5);

?>

<!---用base64來顯示圖片---->
<img src="data:image/png;base64,<?=codepic($code);?>">

<?php

//建立一個可以產生圖形驗證碼的自訂函式
function codepic($code){

   //設定字形檔的路徑(需要絕點路徑)
   $fontpath=realpath("./font/times.ttf");

   //先計算出字形字串佔用的區域坐標
   $text_box=imagettfbbox(20,0,$fontpath,$code);
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
      $textbox=imagettfbbox(20,0,$fontpath,substr($code,$i,1));
      
      //計算字元在Y軸的位置(上邊距+亂數範圍+字形高度)
      $str_y=10+rand(0,15)+$textbox[7]*-1;
      
      //用亂數產生一個-30~30的傾斜角度
      $angle=rand(-30,30);

      //將單一字元畫在底圖上
      imagettftext($img,20,$angle,$str_x,$str_y,$color,$fontpath,substr($code,$i,1));
      
      //計算下一個字元在X軸的位置(將上一個字元的x坐標加上字元的寬度),再加上10px的字元間距
      $str_x=$str_x+$textbox[2]+10;
   }

   //儲存成png檔案
   //imagepng($img,"./code/login_code.png");

   //轉成BASE64後傳到客戶端
   ob_start();           //建立一個緩衝區
   imagepng($img);        //將圖片資料寫入緩衝區
   $output = base64_encode(ob_get_clean()); //將緩衝區中的資料以base64_encode()的方式做編碼
   imagedestroy($img);    //刪除記憶體中的圖片資料
   return $output; 

}

//產生驗證碼的自訂函式
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