<?php include('../assets/header_member.php');?>

    <div class="container">
        <div class="d-flex flex-row">
            <h3 class="font-weight-normal my-0 mr-md-auto font-weight-normal">ご注文を承りました</h3>
        </div>


        <?php

        session_start();
        session_regenerate_id(true); //合言葉を都度変える。セッションハイジャック対策。
        try {
            require_once('../common/common.php');
            $post = sanitize($_POST);

            $onamae = $post['name'];
            $email = $post['email'];
            $postal1 = $post['postal1'];
            $postal2 = $post['postal2'];
            $address = $post['address'];
            $tel = $post['tel'];
            $chumon = $post['chumon'];
            $pass = $post['pass'];
            $danjo = $post['danjo'];
            $birth = $post['birth'];
            echo $onamae . "様<br>" . "ご注文ありがとうございました。<br>" . $email . "宛に注文内容確認メールをお送りしましたのでご確認ください。<br>商品は以下の住所に発送いたします。<br>" . $postal1 . "-" . $postal2 . "<br>" . $address . "<br>" . $tel . "<br>";

            $honbun = '';
            $honbun .= $onamae . "様\n\n" . "この度はご注文ありがとうございました。\n";
            $honbun .= "ご注文商品\n";
            $honbun .= "----------------\n";

            $cart = $_SESSION['cart'];
            $kazu = $_SESSION['kazu'];
            $max = count($kazu);

            include('../assets/db_connect.php');

            for ($i = 0; $i < $max; $i++) {
                $sql = 'SELECT name, price FROM mst_product WHERE code=?';
                $stmt = $dbh->prepare($sql);
                $data[0] = $cart[$i];
                $stmt->execute($data);
                $rec = $stmt->fetch(PDO::FETCH_ASSOC);

                $name = $rec['name'];
                $price = $rec['price'];
                $kakaku[] = $price; //あとで$kakakuの配列使う
                $suryo = $kazu[$i];
                $syokei = $suryo * $price;

                $honbun .= $name . ' ';
                $honbun .= $price . '円 x';
                $honbun .= $suryo . '個 = ';
                $honbun .= $syokei . '円\n';
            }

            $dbh = null;

            $honbun .= "送料は無料です。\nblahblah";

            include('../assets/db_connect.php');

            $sql = 'LOCK TABLES dat_sales WRITE, dat_sales_product WRITE, dat_member WRITE';
            $stmt = $dbh->prepare($sql);
            $stmt->execute();

            $lastmembercode = 0;
            if ($chumon == "chumontouroku") {
                $sql = 'INSERT INTO dat_member (password,name,email, postal1, postal2, address, tel, danjo, born) VALUES (?,?,?,?,?,?,?,?,?)';
                $stmt = $dbh->prepare($sql);
                $data = array();
                $data[] = $pass;
                $data[] = $onamae;
                $data[] = $email;
                $data[] = $postal1;
                $data[] = $postal2;
                $data[] = $address;
                $data[] = $tel;
                if ($danjo == "dan") {
                    $data[] = 1;
                } else {
                    $data[] = 2;
                }
                $data[] = $birth;
                $stmt->execute($data);

                $sql = 'SELECT LAST_INSERT_ID()';
                $stmt = $dbh->prepare($sql);
                $stmt->execute($data);
                $rec = $stmt->fetch(PDO::FETCH_ASSOC);
                $lastmembercode = $rec['LAST_INSERT_ID()'];
            }
            $sql = 'INSERT INTO dat_sales (code_member, name, email, postal1, postal2, address, tel) VALUES (?,?,?,?,?,?,?)';
            $stmt = $dbh->prepare($sql);
            $data = array(); //初期化しないと古い$dataに値が入ったまま
            $data[] = $lastmembercode;
            $data[] = $onamae;
            $data[] = $email;
            $data[] = $postal1;
            $data[] = $postal2;
            $data[] = $address;
            $data[] = $tel;
            $stmt->execute($data);

            $sql = 'SELECT LAST_INSERT_ID()';
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            $rec = $stmt->fetch(PDO::FETCH_ASSOC);
            $lastcode = $rec['LAST_INSERT_ID()'];

            for ($i = 0; $i < $max; $i++) {
                $sql = 'INSERT INTO dat_sales_product (code_sales, code_product, price, quantity) VALUES (?,?,?,?)';
                $stmt = $dbh->prepare($sql);
                $data = array();
                $data[] = $lastcode;
                $data[] = $cart[$i];
                $data[] = $kakaku[$i];
                $data[] = $kazu[$i];
                $stmt->execute($data);
            }

            $sql = 'UNLOCK TABLES';
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            $dbh = null;
            // header('Location: clear_cart.php');
            // exit();
            // clear_cart.phpの中身を持って来る
            // session_start();　すでにセッションがあるので開始不要
            $_SESSION['cart'] = '';
            if (isset($_COOKIE[session_name()]) == true) {
                setcookie(session_name(), '', time() - 42000, '/');
            }
            session_destroy();

            if ($chumon == "chumontouroku") {
                echo "会員登録が完了しました。<br>次回からメールアドレスとパスワードでログインしてください。ご注文が簡単にできるようになります。<br><br>";
            }
        } catch (Exception $e) {
            echo $e;
            exit();
        }


        ?>
        <br>

        <a href="../index.php">商品画面へ</a>

        <?php include('../assets/footer.php');?>