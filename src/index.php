<?php
require_once 'functions.php';

//TODO予約日選択肢配列
$reserve_date_array = [
    "20220601" => "6/1",
    "20220602" => "6/2",
    "20220603" => "6/3"
];

//TODO選択肢配列
$reserve_num_array = [
    "1" => "1",
    "2" => "2",
    "3" => "3"
];

//TODO予約日選択肢配列
$reserve_time_array = [
    "12:00" => "12:00",
    "13:00" => "13:00",
    "14:00" => "14:00"
];

session_start();

$err = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //POSTパラメータから各種入力値を受け取る
    $reserve_date = $_POST['reserve_date'];
    $reserve_num = $_POST['reserve_num'];
    $reserve_time = $_POST['reserve_time'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $comment = $_POST['comment'];

    //各種入力値のバリデーション
    if (!$reserve_date) {
        $err['reserve_date'] = '予約日を入力してください';
    }
    //TODO:予約日はプルダウン設定値を決定後にバリデーション実装

    if (!$reserve_num) {
        $err['reserve_num'] = '人数を入力してください';
    } elseif (!preg_match('/^[0-9]+$/', $reserve_num)) {
        $err['reserve_num'] = '人数を正しく入力してください';
    }

    if (!$reserve_time) {
        $err['reserve_time'] = '予約時間を入力してください';
    }
    //TODO:予約時間はプルダウン設定値を決定後にバリデーション実装

    if (!$name) {
        $err['name'] = '氏名を入力してください';
    } elseif (mb_strlen($name, 'utf-8') > 20) {
        $err['name'] = '名前は20文字以内で入力してください';
    }

    if (!$email) {
        $err['email'] = 'メールアドレスを入力してください';
    } elseif (mb_strlen($email, 'utf-8') > 100) {
        $err['email'] = 'メールアドレスは100文字以内で入力してください';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $err['email'] = 'メールアドレスが不正です';
    }

    if (!$tel) {
        $err['tel'] = '電話番号を入力してください';
    } elseif (mb_strlen($tel, 'utf-8') > 20) {
        $err['tel'] = '電話番号は20文字以内で入力してください';
    } elseif (!preg_match('/^[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}$/', $tel)) {
        $err['tel'] = '電話番号を正しく入力してください';
    }

    if (mb_strlen($comment, 'utf-8') > 2000) {
        $err['comment'] = '備考欄は2000文字以内で入力してください';
    }

    //エラーがなければ次の処理に進む
    if (empty($err)) {
        //各種入力値をセッション変数に保存する
        $_SESSION['RESERVE']['reserve_date'] = $reserve_date;
        $_SESSION['RESERVE']['reserve_num'] = $reserve_num;
        $_SESSION['RESERVE']['reserve_time'] = $reserve_time;
        $_SESSION['RESERVE']['name'] = $name;
        $_SESSION['RESERVE']['email'] = $email;
        $_SESSION['RESERVE']['tel'] = $tel;
        $_SESSION['RESERVE']['comment'] = $comment;

        //予約確認画面へ遷移
        header('Location: confirm.php');
        exit;
    }
} else {
    //セッションに入力情報がある場合は取得する
    if (isset($_SESSION['RESERVE'])) {
        $reserve_date = $_SESSION['RESERVE']['reserve_date'];
        $reserve_num = $_SESSION['RESERVE']['reserve_num'];
        $reserve_time = $_SESSION['RESERVE']['reserve_time'];
        $name = $_SESSION['RESERVE']['name'];
        $email = $_SESSION['RESERVE']['email'];
        $tel = $_SESSION['RESERVE']['tel'];
        $comment = $_SESSION['RESERVE']['comment'];
    } else {
        //存在しない場合は各変数を初期化
        $reserve_date = '';
        $reserve_num = '';
        $reserve_time = '';
        $name = '';
        $email = '';
        $tel = '';
        $comment = '';
    }
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

    <title>ご来店予約</title>
</head>

<body>
    <header>SAMPLE SHOP</header>
    <h1>ご来店予約</h1>
    <form class="m-3" method="post">
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">【1】予約日を選択</label>
            <?= arrayToSelect('reserve_date', $reserve_date_array, $reserve_date) ?>
            <div class="invalid-feedback"><?= $err['reserve_date'] ?></div>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">【2】人数選択</label>
            <?= arrayToSelect('reserve_num', $reserve_num_array, $reserve_num) ?>
            <div class="invalid-feedback"><?= $err['reserve_num'] ?></div>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">【3】予約時間を選択</label>
            <?= arrayToSelect('reserve_time', $reserve_time_array, $reserve_time) ?>
            <div class="invalid-feedback"><?= $err['reserve_time'] ?></div>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">【4】予約情報を入力</label>
            <input type="text" class="form-control <?php if (isset($err['name'])) echo 'is-invalid' ?>" name="name" placeholder="氏名" value="<?= $name ?>">
            <div class="invalid-feedback"><?= $err['name'] ?></div>
        </div>
        <div class="mb-3">
            <input type="email" class="form-control <?php if (isset($err['email'])) echo 'is-invalid' ?>" name="email" placeholder="メールアドレス" value="<?= $email ?>">
            <div class="invalid-feedback"><?= $err['email'] ?></div>
        </div>
        <div class="mb-3">
            <input type="tel" class="form-control <?php if (isset($err['tel'])) echo 'is-invalid' ?>" name="tel" placeholder="電話番号" value="<?= $tel ?>">
            <div class="invalid-feedback"><?= $err['tel'] ?></div>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">【5】備考欄</label>
            <textarea class="form-control <?php if (isset($err['comment'])) echo 'is-invalid' ?>" name="comment" rows="3" placeholder="備考欄"><?= $comment ?></textarea>
            <div class="invalid-feedback"><?= $err['comment'] ?></div>
        </div>

        <div class="d-grid gap-2">
            <button class="btn btn-primary rounded-pill" type="submit">確認画面へ</button>
            <button class="btn btn-secondary  rounded-pill" type="button">戻る</button>
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
