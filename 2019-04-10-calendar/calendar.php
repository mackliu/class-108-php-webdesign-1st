<style>
body{
  font-family:consolas;
}
th{
  border:1px solid gray;
  text-align:center;
  padding:10px;
  height:30px;
  background:lightgreen;
}

td{
  border:1px solid gray;
  text-align:center;
  padding:10px;
  height:80px;
}
</style>
<h1>利用時間函數及巢狀迴圈寫一個月曆</h1>
<table>
<tr>
  <th>星期日</th>
  <th>星期一</th>
  <th>星期二</th>
  <th>星期三</th>
  <th>星期四</th>
  <th>星期五</th>
  <th>星期六</th>
</tr>
<?php

//取得今天的日期
$today=date("Y-m-d");

//取得當月第一天
$thisMonthFirstDay=date("Y-m-1");  

//取得第一天的星期
$theFirstDayWeek=date("w",strtotime($thisMonthFirstDay)); 

//取得當月的天數
$monthDays=date("t",strtotime($thisMonthFirstDay));  


//建立一個陣列用來儲存每一天的字串
for($i=1;$i<=$monthDays;$i++){
  $date[]=date("Y-m-".sprintf("%02d",$i));
}

$index=0;  //初始化索引值

//外層迴圈用來判斷月曆的外觀列數，原則上標頭加五周，六行足夠顯示所有的月份
for($i=0;$i<6;$i++){
  echo "<tr>";

  //內層迴圈用來判斷每一周的每天要顯示什麼內容
  for($j=0;$j<7;$j++){

    //計算格子數，此值用來判斷目前程式執行到那一格(天)
    $num=($i*7+$j);
    if($i==0 && $j<$theFirstDayWeek){

      //判斷如果在第一列，同時所處的格子又比第一天小，表示是上個月的日期，則印出空白格子
      //比如第一天是周三，應該要在第四格(0,1,2,3)，
      //但是格子才算到2，表示還在上個月的日期，因此印出空白格子或上個月的日期
      echo "<td></td>";

    }else if($num>($theFirstDayWeek+$monthDays-1)){  
      
      //除了判斷第一列的空白格，另外也要判斷最後一列的格子是否在當月的天數內
      //比如當月的天數為30天，但是計算的格子數超過最後一天的格子數(第一列的空白格數+當月的天數)時，
      //表示己經走到下一個月了，因此印出空白或下一個月的日期

      echo "<td></td>";

    }else{

      //除了空白格子以外的格子都應該要顯示日期的格子
      //因此根據畫到的天數來取出陣列中的日期字串
        echo "<td>".$date[$index]."</td>";

      //索引值+1
      $index++;

    }
    
  }
  echo "</tr>";

}

?>
</table>
<?php


?>
