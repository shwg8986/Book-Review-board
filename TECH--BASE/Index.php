<?php
session_start();
$username = $_SESSION['name_d'];
if (isset($_SESSION['id'])) { //ログインしているとき
    $msg = 'こんにちは' . htmlspecialchars($username, \ENT_QUOTES, 'UTF-8') . 'さん!!　右上の「レビューを投稿する!!」ボタンをクリックして本のレビューを投稿してください。';
    $link = '<a href="Logout.php">ログアウト</a>';

    // DB接続設定
    $dsn = 'mysql:dbname=tb220754db;host=localhost;charset=utf8';
    $user = 'tb-220754';
    $password = 'DhyvgsTmAg';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    $sql = "CREATE TABLE IF NOT EXISTS table1"
        . " ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name_d char(32),"
        . "bookname_d char(32),"
        . "comment_d TEXT,"
        . "date_d TEXT,"
        . "image_d LONGBLOB"
        . ");";
    $stmt = $pdo->query($sql);

    // $filename = 'mission5-1.txt';

    // フォームから値が送信されてきているかの確認
    if (!empty($_POST['bookname']) and !empty($_POST['comment']) and !empty($_FILES["image"]["name"])) {
        $save = 'img/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $save);

        $image = $_FILES["image"];
        $name = $_POST["name"];
        $bookname = $_POST['bookname'];
        $comment = $_POST['comment'];
        $now_date = date("Y年m月d日 H時i分s秒");
        // $save = 'img/' . basename($_FILES['image']['name']);

        $sql = $pdo->prepare("INSERT INTO table1 (name_d, bookname_d, comment_d, image_d, date_d) VALUES (:name_d, :bookname_d, :comment_d, :image_d, :date_d)");
        $sql->bindParam(':name_d', $name_d, PDO::PARAM_STR);
        $sql->bindParam(':bookname_d', $bookname_d, PDO::PARAM_STR);
        $sql->bindParam(':comment_d', $comment_d, PDO::PARAM_STR);
        $sql->bindParam(':image_d', $image_d, PDO::PARAM_STR);
        $sql->bindParam(':date_d', $date_d, PDO::PARAM_STR);

        if (empty($name)) {
            $name_d = "名無し";
        } else {
            $name_d = $name;
            unset($_SESSION['name']);
            $name = null;
        }
        $bookname_d = $bookname;
        $comment_d = $comment;
        // 	$image_d = file_get_contents($_FILES['image']['tmp_name']);
        $image_d = $_FILES['image']['name'];
        $date_d = $now_date;

        $sql->execute();


        unset($_SESSION['bookname']);
        $bookname = null;
        unset($_SESSION['comment']);
        $comment = null;
        unset($_SESSION['image']);
        $image = null;
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
        <link rel="stylesheet" href="styles.css" type="">


        <title>Book review site</title>

    </head>

    <body>
        <div>
            <header id="global-nav">
                <div class="name">shwg's library</div>
                <nav>
                    <ul class="top-nav">

                        <li>

                            <div id="open" class="rbutton" type="button">
                                レビューを投稿する!!
                            </div>
                            <div id="mask" class="hidden"></div> <!-- グレーのマスク -->
                            <section id="modal" class="hidden">

                                <form enctype="multipart/form-data" action="" method="post">

                                    <div class="form-unit">
                                        <input type="text" name="name" class="" style="width: 400px; margin:0 auto;" value="" placeholder="あなたの名前を入力してください(無記名でも可)">
                                    </div>

                                    <div class="form-unit">
                                        <input type="text" name="bookname" class="" style="width: 400px; margin:0 auto;" value="" placeholder="本の名前を入力してください">
                                    </div>


                                    <div class="form-unit">
                                        <textarea name="comment" rows="8" cols="50" class="" style="margin:0 auto;" value="" placeholder="レビューを入力してください"></textarea>
                                    </div>

                                    <div class="form-unit">
                                        <input type="hidden" name="MAX_FILE_SIZE" value="10000000000" />
                                        <input type="file" name="image" accept=".png, .jpeg, .jpg, .gif,">
                                        <legend>(JPEG,JPG,PNG形式のみ対応)</legend>


                                    </div>


                                    <input type="submit" class="btn btn-primary" style="display: block; width: 400px; margin:0 auto;" value="新規投稿">
                                    <hr>

                                    <div id="close">閉じる</div>
                                </form>

                            </section>

                        </li>

                        <li class="logout">
                            <a href="Logout.php">
                                ログアウト
                            </a>
                        </li>
                    </ul>
                </nav>
            </header>
        </div>
        <div id="container">

            <div id="home" class="big-bg">
                <h3 class="page-title">Welcome to shwg's library !!!</h3>
            </div>



            <div class="alert alert-dark alert-dismissible fade show" role="alert" style="font-size:24px; padding:30px 100px; width: 100%; margin: 0 auto;">
                <strong><?php echo $msg; ?></strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <div>

                <?php

                $sql = 'SELECT * FROM table1';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row) {
                    //$rowの中にはテーブルのカラム名が入る
                ?>
                    <section class="animation">

                        <div class="pic1">
                            <img src="img/<?php echo $row['image_d']; ?>" alt="本の写真です" width="400px" height="280px">
                        </div>

                        <div class="doc1">
                            <div>
                                <h1>
                                    <?php echo $row['id'] . ':' . $row['bookname_d'] . '<br>'; ?>
                                </h1>
                            </div>

                            <div class="desc1">
                                <p>
                                    <?php echo $row['comment_d']; ?>
                                </p>

                                <p><?php echo "投稿日時 : " . $row['date_d'] ?></p>
                                <p><?php echo "　　by　" . $row['name_d'] . "さん"  ?></p>

                            </div>
                    </section>

                <?php    }  ?>

            </div>
        </div>

        <footer>
            <div class="footer-text">
                <p>(c) 2020 SHOGO SHIMADA</p>
            </div>
        </footer>
        <script src="main.js"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script>
            $(".page-title").hide().fadeIn(3000);
            $(function() {
                $(window).on('load scroll', function() {
                    $('.animation').each(function() {
                        //ターゲットの位置を取得
                        var target = $(this).offset().top;
                        //スクロール量を取得
                        var scroll = $(window).scrollTop();
                        //ウィンドウの高さを取得
                        var height = $(window).height();
                        //ターゲットまでスクロールするとフェードインする
                        if (scroll > target - height) {
                            //クラスを付与
                            $(this).addClass('active');
                        }
                    });
                });
            });
        </script>
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


<?php
} else { //ログインしていない時
    $msg = 'ログインしていません';
    $link = '<a href="First.php">ログインへ</a>';

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


        <title>Book review site</title>

    </head>


    <body class="text-center p-3 mb-2 bg-light text-dark">
        <div class="alert alert-primary" style="padding: 70px;width: 500px; margin: 0 auto;">
            <h2><?php echo $msg; ?></h2>
            <?php echo $link; ?>
        </div>
    </body>

    </html>

<?php
}

?>
