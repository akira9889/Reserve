<?php
require_once 'config/config.php';
require_once 'functions.php';

session_start();

$pdo = connect_db();

$session_reserve = $_SESSION['RESERVE'];
$reserve_date = $session_reserve['reserve_date'];
$reserve_time = $session_reserve['reserve_time'];
$reserve_num = $session_reserve['reserve_num'];
$name = $session_reserve['name'];
$email = $session_reserve['email'];
$tel = $session_reserve['tel'];
$comment = $session_reserve['comment'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // セッション情報をreserveテーブルにINSERT
    $sql = "INSERT INTO reserve(reserve_date, reserve_time, reserve_num, name, email, tel, comment) VALUES(:reserve_date, :reserve_time, :reserve_num, :name, :email, :tel, :comment)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue('reserve_date', $reserve_date, PDO::PARAM_STR);
    $stmt->bindValue('reserve_time', $reserve_time, PDO::PARAM_STR);
    $stmt->bindValue('reserve_num', (int)$reserve_num, PDO::PARAM_INT);
    $stmt->bindValue('name', $name, PDO::PARAM_STR);
    $stmt->bindValue('email', $email, PDO::PARAM_STR);
    $stmt->bindValue('tel', $tel, PDO::PARAM_STR);
    $stmt->bindValue('comment', h($comment), PDO::PARAM_STR);
    $stmt->execute();

    $stmt = null;
    $pdo = null;

    //セッション情報を消去
    unset($_SESSION['RESERVE']);

    //予約者にメール送信
    $to = $session_reserve['email'];
    $subject = "予約確認メール";
    $reserve_date = date('Y年 n月 j日', strtotime($reserve_date));
    $message = "
                以下の内容で予約いたしました。

                ＝＝＝＝＝＝＝＝＝＝
                予約日：{$reserve_date}
                予約人数：{$reserve_num}人
                予約時間：{$reserve_time}
                氏名：{$name}様
                備考欄：{$comment}
                ＝＝＝＝＝＝＝＝＝＝

                sampleshop.jp
                ";
    $headers = "From: from@sampleshop.jp";
    mail($to, $subject, $message, $headers);

    // 予約完了画面へ遷移
    header('Location: /complete.php');
    exit;
}
$path = '';
$page_title = '予約内容確認';
?>
<!doctype html>
<html lang="ja">

<?php include('templates/head_tag.php'); ?>

<body>
    <?php include('templates/header.php'); ?>
    <table class="table">
        <tbody>
            <tr>
                <th scope="row">日時</th>
                <td><?= time_format_dw($reserve_date) ?> 　予約時間 <?= $reserve_time ?></td>
            </tr>
            <tr>
                <th scope="row">人数</th>
                <td><?= $reserve_num ?></td>
            </tr>
            <tr>
                <th scope="row">氏名</th>
                <td><?= h($name) ?></td>
            </tr>
            <tr>
                <th scope="row">メールアドレス</th>
                <td><?= $email ?></td>
            </tr>
            <tr>
                <th scope="row">電話番号</th>
                <td><?= $tel ?></td>
            </tr>
            <tr>
                <th scope="row">備考欄</th>
                <td><?= h(nl2br($comment)) ?></td>
            </tr>
        </tbody>
    </table>

    <form method="post">
        <div class="d-grid gap-2  mx-3">
            <button class="btn btn-primary rounded-pill">予約確定</button>
            <a class="btn btn-secondary  rounded-pill" href="/">戻る</a>
        </div>
    </form>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
