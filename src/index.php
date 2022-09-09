<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //POSTパラメータから各種入力値を受け取る
    $reserve_date = $_POST['reserve_date'];
    $reserve_num = $_POST['reserve_num'];
    $reserve_time = $_POST['reserve_time'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $tel = $_POST['tel'];
    $comment = $_POST['comment'];

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
            <select class="form-select" name="reserve_date">
                <option selected>日付</option>
                <option value="6/1">6/1</option>
                <option value="6/2">6/2</option>
                <option value="6/3">6/3</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">【2】人数選択</label>
            <select class="form-select" name="reserve_num">
                <option selected>人数</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">【3】予約時間を選択</label>
            <select class="form-select" name="reserve_time">
                <option selected>時間</option>
                <option value="12:00">12:00</option>
                <option value="13:00">13:00</option>
                <option value="14:00">14:00</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="exampleFormControlInput1" class="form-label">【4】予約情報を入力</label>
            <input type="text" class="form-control" name="name" placeholder="氏名">
        </div>
        <div class="mb-3">
            <input type="email" class="form-control" name="email" placeholder="メールアドレス">
        </div>
        <div class="mb-3">
            <input type="tel" class="form-control" name="tel" placeholder="電話番号">
        </div>
        <div class="mb-3">
            <label for="exampleFormControlTextarea1" class="form-label">【5】備考欄</label>
            <textarea class="form-control" name="comment" rows="3" placeholder="備考欄"></textarea>
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
