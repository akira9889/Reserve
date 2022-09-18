<?php
require_once 'config/config.php';
require_once 'functions.php';

session_start();

$pdo = connect_db();

$session_reserve = $_SESSION['RESERVE'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $reserve_date = $session_reserve['reserve_date'];

    // セッション情報をreserveテーブルにINSERT
    $sql = "INSERT INTO reserve(reserve_date, reserve_time, reserve_num, name, email, tel, comment) VALUES(:reserve_date, :reserve_time, :reserve_num, :name, :email, :tel, :comment)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue('reserve_date', $reserve_date, PDO::PARAM_STR);
    $stmt->bindValue('reserve_time', $session_reserve['reserve_time'], PDO::PARAM_STR);
    $stmt->bindValue('reserve_num', (int)$session_reserve['reserve_num'], PDO::PARAM_INT);
    $stmt->bindValue('name', $session_reserve['name'], PDO::PARAM_STR);
    $stmt->bindValue('email', $session_reserve['email'], PDO::PARAM_STR);
    $stmt->bindValue('tel', $session_reserve['tel'], PDO::PARAM_STR);
    $stmt->bindValue('comment', $session_reserve['comment'], PDO::PARAM_STR);
    $stmt->execute();

    $stmt = null;
    $pdo = null;

    //セッション情報を消去
    $_SESSION = array();
    session_destroy();

    // 予約完了画面へ遷移
    header('Location: /complete.php');
    exit;
}
?>
<!doctype html>
<html lang="ja">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Original CSS -->
    <link href="/css/style.css" rel="stylesheet">

    <title>予約内容確認</title>
</head>

<body>
    <header>SAMPLE SHOP</header>
    <h1>予約内容確認</h1>
    <table class="table">
        <tbody>
            <tr>
                <th scope="row">日時</th>
                <td><?= time_format_dw($_SESSION['RESERVE']['reserve_date'])  ?> <?= $_SESSION['RESERVE']['reserve_time'] ?></td>
            </tr>
            <tr>
                <th scope="row">人数</th>
                <td><?= $_SESSION['RESERVE']['reserve_num'] ?></td>
            </tr>
            <tr>
                <th scope="row">氏名</th>
                <td><?= $_SESSION['RESERVE']['name'] ?></td>
            </tr>
            <tr>
                <th scope="row">メールアドレス</th>
                <td><?= $_SESSION['RESERVE']['email'] ?></td>
            </tr>
            <tr>
                <th scope="row">電話番号</th>
                <td><?= $_SESSION['RESERVE']['tel'] ?></td>
            </tr>
            <tr>
                <th scope="row">備考欄</th>
                <td><?= nl2br($_SESSION['RESERVE']['comment']) ?></td>
            </tr>
        </tbody>
    </table>

    <form method="post">
        <div class="d-grid gap-2  mx-3">
            <button class="btn btn-primary rounded-pill">予約確定</button>
            <a class="btn btn-secondary  rounded-pill" href="/">戻る</a>
        </div>
    </form>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
</body>

</html>
