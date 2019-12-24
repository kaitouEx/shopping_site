<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>ろくまる農園</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

</head>

<body>
    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-5 mb-3 bg-white border-bottom shadow-sm">
        <h2 class="my-0 mr-md-auto font-weight-normal"><a href="../shop/index.php" class="">八百屋のすどう</a></h2>
        <div class="flex-row-reverse">
            <?php
            session_start();
            session_regenerate_id(true);
            if (isset($_SESSION['member_login']) == false) {
                echo "ログインされていません。<br>";
                echo '<a href="./member_login.php">ログインする</a><br>';
                //    exit();
            } else {
                echo "ようこそ、" . $_SESSION['member_name'] . "様<br>";
                echo '<a href="member_logout.php">ログアウト</a><br><br>';
            }

            ?>
        </div>
    </div>
    <div class="container">
        <div class="d-flex flex-row">
            <h3 class="font-weight-normal my-0 mr-md-auto font-weight-normal">商品情報参照</h3>
        </div>

        <?php

        try {
            require_once('../common/common.php');
            $post = sanitize($_POST);
            // $pro_code = $_GET['procode'];
            // $kazu = $_GET['kazu'];
            $pro_code = $post['procode'];
            $num = (int) $post['kazu'];
            if (isset($_SESSION['cart']) == true) {
                $cart = $_SESSION['cart'];
                $kazu = $_SESSION['kazu'];
                if (in_array($pro_code, $cart)) {
                    //すでに商品が$cartの2番目に入っていたら、
                    //2番めの数を増やす
                    $junban = array_search($pro_code, $cart); //結果は2
                    $kazu[$junban] += $num;
                    //＄_SESSION['kazu']も更新
                    $_SESSION['kazu'] = $kazu;
                } else {
                    //カートにまだ同じ商品がないときは、$cartに$pro_codeと数量を追加する
                    $kazu[] = $num;
                    $cart[] = $pro_code;
                }
            } else {
                //セッションにカートがないときは、$cartに$pro_codeと数量を追加する
                $kazu[] = $num;
                $cart[] = $pro_code;
            }

            $_SESSION['cart'] = $cart;
            $_SESSION['kazu'] = $kazu;
        } catch (Exception $e) {
            echo $e;
            exit();
        } ?>
        カートに追加しました。<br>
        <!-- TODO:カートの中身を表示 -->

        <a class="btn btn-secondary mt-3" href="index.php">戻る</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html>