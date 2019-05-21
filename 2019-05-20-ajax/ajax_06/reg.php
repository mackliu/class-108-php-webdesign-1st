  <style>
    .errmeg {
      color: red;
      font-size: 12px;
      font-family: "微軟正黑體";
      text-align: left;
    }
  </style>
  <h3 class="meg" style="text-align:center"></h3>
  <table class="reg">
    <tr>
      <td>帳號</td>
      <td>
        <input type="text" name="acc" id="acc" value="">
        <p class="errmeg" id="erracc"></p>
      </td>
    </tr>
    <tr>
      <td>密碼</td>
      <td><input type="password" name="pw" id="pw" value="">
        <p class="errmeg" id="errpw"></p>
      </td>
    </tr>
    <tr>
      <td>本名</td>
      <td>
        <input type="text" name="name" id="name" value="">
        <p class="errmeg" id="errname"></p>
      </td>
    </tr>
    <tr>
      <td><input type="button" value="新增" onclick="reg()"></td>
      <td><input type="reset" value="重置"></td>
    </tr>
  </table>