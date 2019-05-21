<!---上方導覽列--->

  <ul class="nav">
    <li><a href="index.php">首頁</a></li>
    <li><a href="javascript:loadpage('reg.php')">註冊會員</a></li>
    <?php 
        include_once "base.php";
        //利用session來判斷使用者是否為管理者，如果是管理者就顯示管理頁面的連結
        if(!empty($_SESSION['login']) && $_SESSION['user']=='admin'){

          //將連結改成以js函式的方來呼叫，如果遇到單引號衝突的地方可以使用&#39;來代替單引號
          echo "<li><a href='javascript:loadpage(&#39;admin.php&#39;)'>管理頁面</a></li>";
        }
        ?>
    <?php 
        //利用session來判斷使用者的登入狀態用以決定要顯示登出或登入的功能
        if(!empty($_SESSION['login'])){
          echo "<li><a href='logout.php'>登出</a></li>";
        }else{
          echo "<li><a href='javascript:loadpage(&#39;login.php&#39;)'>登入</a></li>";
        }
      ?>
  </ul>
