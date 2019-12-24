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
            session_regenerate_id(true); //合言葉を都度変える。セッションハイジャック対策。

            if (isset($_SESSION['login']) == false) {
                echo "ログインされていません。<br>";
                echo '<a href="../staff_login/staff_login.php">ログインする</a><br>';
                exit();
            } else {
                echo '管理者モード<br>' . $_SESSION['staff_name'] . "さんログイン中<br>";
            }

            ?>
        </div>
    </div>
    <div class="container">
        <div class="d-flex flex-row">
            <h3 class="font-weight-normal my-0 mr-md-auto font-weight-normal mb-3">スタッフ一覧管理</h3>
        </div>
        <?php

        try {
            require_once("../common/common.php");
            $post = sanitize($_POST);

            $dsn='mysql:dbname=tqmsbzgg_shop;host=localhost;charset=utf8';
            $user='tqmsbzgg_shop';
            $password='%RdFsbr)I})8';
            $dbh = new PDO($dsn, $user, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = 'SELECT code, name FROM mst_staff WHERE 1';
            $stmt = $dbh->prepare($sql);
            $stmt->execute();

            $dbh = null;

            echo '<form action="staff_branch.php" method="post">';
            while (true) {
                $rec = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($rec == false) {
                    break;
                } else {
                    echo '<input type="radio" name="staffcode" value="' . $rec['code'] . '"<br>';
                    echo $rec['name'] . "<br>";
                }
            }
            echo '<input type="submit" name="add" value="追加">';
            echo '<input type="submit" name="view" value="参照">';
            echo '<input type="submit" name="edit" value="修正">';
            echo '<input type="submit" name="delete" value="削除">';
            echo '</form>';
        } catch (Exception $e) {
            echo $e;
            exit();
        } ?>
        <br>
        <a href="../staff/staff_list.php">スタッフ管理</a><br>
        <a href="../product/pro_list.php">商品管理</a><br>
        <a href="../shop/index.php">商品一覧</a><br>
        <a href="../order/order_download.php">注文書ダウンロード</a><br>
        <a href="../staff_login/staff_logout.php">ログアウト</a><br>
        <a href="../staff_login/staff_top.php">管理者トップメニューへ</a><br>
    </div>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html>