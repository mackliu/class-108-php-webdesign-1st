/*******************************************
 * 顯示上方導覽列的函式
 * 1.在首頁載入時向api請求提供導覽列的選單資料
 * 2.在收到資料後，以JS的方法將資料寫入到導覽列
 * 
 *******************************************/
function getmenu(){
  $.get("./api/api_nav.php",function(res){

  //將取得的回應解析成js的物件
  let menu=JSON.parse(res)

  //建立一個字串用來串接所有選單的內容
  let list=`<ul class="nav">`;
  menu.forEach(function(val){

    //利用迴圈把所有物件的內容加上html的標籤並串成一個字串
    list=list+`<li><a href="${val.url}">${val.title}</a></li>`;
  })

  //在字串最後加上結束的標籤
  list=list+"</ul>";

  //在指定的區塊放入字串的內容並在網頁上顯示出來
  $("#nav").html(list)

})
}

/*******************************************
 * 顯示左方側邊欄選單的函式
 * 1.在首頁載入時向api請求提供側邊欄的選單資料
 * 2.在收到資料後，以JS的方法將資料寫入到導覽列
 * 
 *******************************************/
function getsidebar(){
  $.get("./api/api_sidebar.php",function(res){

    let menu=JSON.parse(res);

    let list="";
    menu.forEach(function(val){
      list=list+`<li><a href="javascript:loadpage('#content','${val.url}')">${val.title}</a></li>`;
    })
    
    $(".menu").html(list)
  })

}


/*******************************************
 * 載入頁面的函式
 * 1.提供dom及網址後，可以將指定的檔案內容載入到指定的dom中
 * 
 *******************************************/
function loadpage(dom,url){

  //利用.load()的回呼函式來判斷url是否存在，
  //如果檔案不存在，則顯示錯誤訊息，如果檔案存在，則顯示檔案回傳的結果
  $(dom).load(url,function(res,status){
    if(status=='error'){
      $(dom).html("抱歉，檔案不存在，請聯絡網站管理員");
    }else{
      $(dom).html(res)
    }
  });
}


/*******************************************
 * 登入功能專用的函式
 * 1.以js的方式取得欄位的輸入值
 * 2.將值傳送到api去取得結果
 * 3.根據結果來決定頁面在登入後要呈現的狀態
 * 
 *******************************************/
function login(){
  let acc=$("#acc").val();
  let pw=$("#pw").val();

  $.post("./api/api_login.php",{acc,pw},function(res){

    //根據回傳的值來決定要進行什麼動作，
    //要注意的是PHP以echo 回傳的值原則上都是字串
    switch(res){
      case "1":
        //如果是admin登入成功，則載入管理頁面同時重置一次導覽列及側邊欄
        loadpage("#content","admin.php");
        getmenu();
        getsidebar();
      break;
      case "2":
        //如果是一般會員登入成功，則載入會員頁面同時重置一次導覽列及側邊欄
        loadpage("#content","member.php");
        getmenu();
        getsidebar();
      break;
      case "3":
        //如果是帳密錯誤，則以彈出視窗來顯示提示
        alert("帳號密碼錯誤,請重新輸入");
      break;
      case "4":
        //如果是欄位空白，則以彈出視窗來顯示提示
        alert("欄位不可空白");
      break;
    }
  })

}


/*******************************************
 * 註冊功能專用的函式
 * 1.以js的方式取得欄位的輸入值
 * 2.將值傳送到api去取得結果
 * 3.根據結果來決定要在前端顯示的訊息(新增成功或是欄位錯誤)
 * 
 *******************************************/
function reg(){
  //先取得各欄位當前的值
  let acc=$("#acc").val();
  let pw=$("#pw").val();
  let name=$("#name").val();

  //將取得的值以post的方式傳送到api_reg.php中
  $.post("./api/api_reg.php",{acc,pw,name},function(res){
    
    //以json格式解析回應的內容
    let meg=JSON.parse(res)

    //如果回應的內容中有chkAcc這個內容的話，表示帳號的檢查出了問題
    if(typeof(meg.chkAcc)!='undefined'){
      $(".meg").html("帳號重覆")
    }

    //如果回應的內容中有status這個內容話，表示資料內容正確，並執行了新增到資料表的動作
    //status的值表示新增是否成功或異常
    if(typeof(meg.status)!='undefined'){
      $(".meg").html(meg.status)
    }

    //回應的內容應該會有err這個內容，包含了三個欄位的錯誤狀況，
    //如果該欄位沒有錯誤，則值為空白
    if(typeof(meg.err)!='undefined'){

      //將每個欄位的錯誤資訊寫入對應的html標籤內
      $("#erracc").html(meg.err.acc);
      $("#errpw").html(meg.err.pw);
      $("#errname").html(meg.err.name);
    }
  })
}

/*******************************************
 * 登出功能專用的函式
 * 1.通知logout.php將session清空
 * 2.前端執行initsite來重置各個區塊內容
 * 
 *******************************************/
function logout(){
  $.get("logout.php",function(){
    initsite();
  })
}

/*******************************************
 * 初始化首頁功能專用的函式
 * 1.使用者到首頁時會先執行的程式
 * 2.以ajax的方式從各api呼叫導覽列、側邊欄及內容的資料
 * 
 *******************************************/
function initsite(){
  getmenu();
  getsidebar();
  loadpage("#content","content.php")

}