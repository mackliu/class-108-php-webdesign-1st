<style>
/*nth-child 子元素選擇器*/
td:nth-child(1){
  background:lightblue;
}
tr:nth-child(1){
  background:lightblue;
}
td{
  border:1px solid black;
  width:40px;
  height:40px;
  text-align:center;
  padding:5px;
}
</style>
<!----先建立一個表頭的欄位---->
<h1>表格式的九九乘法表</h1>
<table>
<tr>
  <td></td>
  <td>1</td>
  <td>2</td>
  <td>3</td>
  <td>4</td>
  <td>5</td>
  <td>6</td>
  <td>7</td>
  <td>8</td>
  <td>9</td>
</tr>
<!----第二列開始插入PHP程式碼---->
<?php
/*以表格的方式來呈現九九乘法表*/

//外層迴圈通常用來決定要跑幾列
for($j=1 ; $j <= 9 ;$j++){

//每一列的開頭要先加上<tr>標籤及右側的標頭內容  
echo "<tr><td>".$j."</td>";

  //內層迴圈通常用來決定要跑幾欄
  for($i=1;$i<=9 ;$i++){

    //逐欄印出需要的HTML標籤及內容
    echo "<td>".$i*$j ."</td>\n";

  }

//每列結束時要記得加上列的結束標籤
echo "</tr>\n";

}
?>

<!----表格的結束標籤---->
</table>