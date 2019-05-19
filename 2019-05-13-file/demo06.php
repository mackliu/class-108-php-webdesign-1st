<?php

/****************************************************
1.fopen(file,mode,path,context)
  ->r 唯讀
  ->r+ 讀寫，由檔案開頭開始
  ->w 寫入，由檔案開頭開始並將檔案清空，無檔案則建立檔案
  ->w+ 讀寫，由檔案開頭開始並將檔案清空，無檔案則建立檔案
　->a 寫入，由檔案尾端開始，無檔案則建立檔案
　->a+ 寫入，由檔案尾端開始，無檔案則建立檔案
  ->wb 寫入，轉換換行格式為\r\n
  ->file_exists() 判斷檔案是否存在
2.建立要寫入檔案的內容 str
  ->斷行 \n or \r\n
3.fwrite(file,str) 寫入檔案中
4.fclose() 關閉檔案
*****************************************************/
/****************************************************
1.fgets(file,length) 一次讀取一行的資料出來
2.fgetc(file) 一次讀出一個字元來
3.fgetcsv(file,length,separator,enclosure) 一次一行，並解析字串為陣列
4.feof() 檢查是否己經到了檔案的最尾端
******************************************************/
//練習:讀入一個CSV檔案，把內容寫入資料表中
//需要先檢視過一次檔案的內容，並先建立資料表及需要的欄位

//建立資料表連線
$dsn="mysql:host=localhost;charset=utf8;dbname=108_php_01";
$pdo=new PDO($dsn,"root","");

//以唯讀模式開啟檔案
$file=fopen("demo06.csv","r");

$count=0;  //計算處理到第幾行內容
$insert=0; //計算成功插入資料表的行數

//用feof()判斷是否到檔尾
while(!feof($file)){

 //取出一行資料
  $line=fgets($file); 

  //第一行的資料不要處理
  if($count>0){   

    //用explode函式來拆解字串成為一個陣列
    $data=explode(',',$line); 

    //判斷空白行，非空白行才需要寫入，$data的元素個數大於2個以上時表示該行有資料
    if(count($data)>1){  
    
      //建立SQL insert語法 
      $sql="insert into labor (
                                `year`,
                                `name`,
                                `description`,
                                `duration`,
                                `url`) 
                        values(
                                '".$data[0]."',
                                '".$data[1]."',
                                '".$data[2]."',
                                '".$data[3]."',
                                '".$data[4]."')";
         
      //寫入資料表
      $pdo->query($sql);
      echo "寫入第".$count."行資料<br>";
      $insert++; //寫入成功的話變數insert加1
    }
  }
  $count++; //迴圈完成一次變數count加1
}

//結束時在網頁上顯示處理狀況
echo "共處理".$count."行資料";
echo "共加入".$insert."筆資料";


?>