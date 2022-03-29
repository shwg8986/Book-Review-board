<?php
$dsn = 'mysql:dbname=tb220754db;host=localhost';
$user = 'tb-220754';
$password = 'DhyvgsTmAg';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));


$sql = "CREATE TABLE IF NOT EXISTS users"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name_d char(32),"
	. "mail_d char(64),"
	. "pass_d char(32)"
	.");";
$stmt = $pdo->query($sql);



//フォームからの値をそれぞれ変数に代入
$name_d = $_POST['name'];
$mail_d = $_POST['mail'];
$pass_d = $_POST['pass'];



//フォームに入力されたmailがすでに登録されていないかチェック
$sql = "SELECT * FROM users WHERE mail_d = :mail_d";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':mail_d', $mail_d , PDO::PARAM_STR);
$stmt->execute();
$member = $stmt->fetch();

if ($member['mail_d'] === $mail_d) {
    $msg = 'そのメールアドレスは既に使用されています。';
    $link = '<a href="First.php">戻る</a>';
}
elseif
(strlen($pass_d) < 4 or strlen($pass_d) > 8){
    $msg = 'パスワードは4〜8桁の間で設定してください。';
    $link = '<a href="First.php">戻る</a>';
}
else {
    //登録されていなければinsert
    $sql = $pdo -> prepare("INSERT INTO users (name_d, mail_d, pass_d) VALUES (:name_d, :mail_d, :pass_d)");
    $sql -> bindParam(':name_d', $name_d, PDO::PARAM_STR);
    $sql -> bindParam(':mail_d', $mail_d, PDO::PARAM_STR);
    $sql -> bindParam(':pass_d', $pass_d, PDO::PARAM_STR);
    $name_d = $_POST['name'];
    $mail_d = $_POST['mail'];
    $pass_d = $_POST['pass'];
    $sql -> execute();

    $msg = '会員登録が完了しました';
    $link = '<a href="First.php">戻る</a>';

//     $sql = 'SELECT * FROM users';
//     $stmt = $pdo->query($sql);
//     $results = $stmt->fetchAll();
// 	foreach ($results as $row){
// 		//$rowの中にはテーブルのカラム名が入る
// 		echo $row['id'].',';
// 		echo $row['name_d'].',';
// 		echo $row['mail_d'].',';
// 		echo $row['pass_d'].'<br>';
// 	echo "<hr>";
// 	}

}
?>

<!doctype html>
<html lang="ja">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">


    <title>登録確認</title>

  </head>

  <body class="text-center p-3 mb-2 bg-light text-dark">

    <div class="alert alert-primary" style="padding: 70px;width: 500px; margin: 0 auto;" >
    <h3><?php echo $msg; ?></h3><!--メッセージの出力-->
    <?php echo $link; ?>
    </div>




    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <!-- Option 2: jQuery, Popper.js, and Bootstrap JS
      <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    -->
  </body>
</html>
