<div id="admin"></div>

<script>
getMemList();

/********************
 * 
 * 取得會員列表函式
 * 
 ********************/
function getMemList(){
  //向api請求取得會員資料
  $.get("./api/api_admin_list.php",function(res){

    //將資料轉成js的物件格式
    let list=JSON.parse(res);

    //建立字串
    let str="<ul>";

    //利用迴圈幫每個會員的資料加上html標籤
    list.forEach(function(mem){
      str=str+`<li><a href="javascript:setMemPr(${mem.id})">${mem.acc}-${mem.name}</a></li>`;
    })

    //加上清單的結束標籤
    str=str+"</ul>";

    //在指定的區塊中寫入字串並在網頁上顯示出來
    $("#admin").html(str);
  })
}

/********************
 * 
 * 設定會員權限函式
 * 
 ********************/
function setMemPr(id){

  //將函式傳入的id值傳到api以取得指定會員的資料
  $.get("./api/api_admin_user.php",{id},function(res){

    //將會員資料轉成js物件格式
    let user=JSON.parse(res);

    //建立要在網頁上顯示的字串及html內容，並在必要的地方插入資料
    let str=`acc=${user.acc}<br>name=${user.name}<br>`;
    
    str=str+`<input type="checkbox" name="per[]" value="1">關於我們<br>
             <input type="checkbox" name="per[]" value="2">最新資訊<br>
             <input type="checkbox" name="per[]" value="3">活動資訊<br>
             <input type="checkbox" name="per[]" value="4">產品訂購<br>
             <input type="checkbox" name="per[]" value="5">留言板<br>
             <input type="checkbox" name="per[]" value="6">生活留影<br>
             <input type="button" value="確認送出" onclick="updateUser(${user.id})">    
            `;
    
    //在指定的區塊顯示字串內容
    $("#admin").html(str);

    //利用迴圈更新每個選項的勾選狀態
    user.permission.forEach(function(pr){

      $(`input[value='${pr}']`).prop("checked",true);

    })

  })
}


/********************
 * 
 * 更新會員權限函式
 * 
 ********************/
function updateUser(id){

  //先取得所有的input元件
  let input=$("input[name='per[]']");
  //console.log(input)

  //建立一個js陣列來儲存使用者勾選的權限
  let pr=Array();

      //利用jQuery的each()函式來逐一取得個別的input元件
      input.each(function(i,el){

        //判斷個別的input元件中的checked屬性是否為true，true表示被勾選
        //如果是被勾選的話，則把元件的value值存到陣列中
        if(el.checked==true){

          pr.push(el.value);

        }
      })
  //console.log(id,pr)

  //取得權限勾選的陣列後，將會員id與權限陣列傳送到api去進行資料更新的動作
  $.post("./api/api_admin_user_update.php",{id,pr},function(res){
   // console.log(res);

   //依照回應值來決定要在畫面顯示什麼訊息
    if(res=="1"){
      $("#admin").html("更新成功");
    }else{
      $("#admin").html("更新異常");
    }
  })

}
</script>