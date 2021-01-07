<?php
$user = 'tes';
$password = 'tes';

if (!isset($_SERVER['PHP_AUTH_USER'])) {
  header('WWW-Authenticate: Basic realm="Private Page"');
  header('HTTP/1.0 401 Unauthorized');

  die('このページを見るにはログインが必要です');
} else {
  if (
    $_SERVER['PHP_AUTH_USER'] != $user
    || $_SERVER['PHP_AUTH_PW'] != $password
  ) {

    header('WWW-Authenticate: Basic realm="Private Page"');
    header('HTTP/1.0 401 Unauthorized');
    die('このページを見るにはログインが必要です');
  }
}

// データベース呼び出し
include('function.php'); // 関数を記述したファイルの読み込み
$pdo = connect_to_db(); // 関数実行

// 参照はSELECT文！
$sql = 'SELECT * FROM form_table2';
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

// データを表示しやすいようにまとめる
if ($status == false) {
  $error = $stmt->errorInfo();
  exit('sqlError:' . $error[2]);
} else {
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $output = "";
  foreach ($result as $record) {
    $output .= "<tr>";
    $output .= "<td>{$record["company"]}</td>";
    $output .= "<td>{$record["name"]}</td>";
    $output .= "<td>{$record["phone"]}</td>";
    $output .= "<td>{$record["email"]}</td>";
    $output .= "<td>{$record["content"]}</td>";
    // edit deleteリンクを追加
    $output .= "<td>
     <a href='./form_edit.php?id={$record["id"]}'>編集</a>
     </td>";
    $output .= "<td>
     <a href='./form_delete.php?id={$record["id"]}'>削除</a>
     </td>";
    $output .= "</tr>";
  }
  // $valueの参照を解除する．解除しないと，再度foreachした場合に最初からループしない
  // 今回は以降foreachしないので影響なし
  unset($record);
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.scss">
  <title>問い合わせ一覧</title>

  <!-- フォントの設定 -->
  <script>
    (function(d) {
      var config = {
          kitId: 'cvg5cxw',
          scriptTimeout: 3000,
          async: true
        },
        h = d.documentElement,
        t = setTimeout(function() {
          h.className = h.className.replace(/\bwf-loading\b/g, "") + " wf-inactive";
        }, config.scriptTimeout),
        tk = d.createElement("script"),
        f = false,
        s = d.getElementsByTagName("script")[0],
        a;
      h.className += " wf-loading";
      tk.src = 'https://use.typekit.net/' + config.kitId + '.js';
      tk.async = true;
      tk.onload = tk.onreadystatechange = function() {
        a = this.readyState;
        if (f || a && a != "complete" && a != "loaded") return;
        f = true;
        clearTimeout(t);
        try {
          Typekit.load(config)
        } catch (e) {}
      };
      s.parentNode.insertBefore(tk, s)
    })(document);
  </script>
  <!-- フォントの設定ここまで -->
</head>

<html>

<head>
  <title>問い合わせリスト一覧</title>
</head>

<body>

  <h1>問い合わせ一覧</h1>
  <a href="./form_txt_input.php">入力画面</a>
  <table>
    <thead>
      <tr>
        <th>会社名</th>
        <th>名前</th>
        <th>電話番号</th>
        <th>メールアドレス</th>
        <th>内容</th>
      </tr>
    </thead>
    <tbody>
      <!-- ここに<tr><td>deadline</td><td>todo</td><tr>の形でデータが入る -->
      <?= $output ?>
    </tbody>
  </table>
</body>

</html>