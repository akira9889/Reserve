<?php
require_once '../config/config.php';
require_once '../functions.php';
//
$reservable_date_array = [
    '1' => '1日前',
    '2' => '2日前',
    '3' => '3日前',
    '4' => '4日前',
    '5' => '5日前',
    '6' => '6日前',
    '7' => '7日前',
];

$start_time_array = [];
for ($i = 0; $i < 24; $i++) {
    $start_time_array = array_merge($start_time_array, array_combine([sprintf('%02d', $i) . ':00:00'], [sprintf('%02d', $i) . ':00']));
}

$end_time_array = [];
for ($i = 0; $i < 24; $i++) {
    $end_time_array = array_merge($end_time_array, array_combine([sprintf('%02d', $i) . ':00:00'], [sprintf('%02d', $i) . ':00']));
}

$max_reserve_num_array = array_combine(range(1,10), range(1,10));

$pdo = connect_db();

$sql = "SELECT * FROM shop";
$stmt = $pdo->query($sql);
$shop = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $reservable_date = $_POST['reservable_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $max_reserve_num = $_POST['max_reserve_num'];

    if ($shop) {
        $sql = "UPDATE shop SET reservable_date = :reservable_date, start_time = :start_time, end_time = :end_time, max_reserve_num = :max_reserve_num";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue('reservable_date', $reservable_date, PDO::PARAM_STR);
        $stmt->bindValue('start_time', $start_time, PDO::PARAM_STR);
        $stmt->bindValue('end_time', $end_time, PDO::PARAM_STR);
        $stmt->bindValue('max_reserve_num', (int)$max_reserve_num, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: " . $_SERVER['PHP_SELF']);
    } else {
        // 設定情報をshopテーブルにINSERT
        $sql = "INSERT INTO shop(login_id, login_password, reservable_date, start_time, end_time, max_reserve_num) VALUES('reserve', 'pass', :reservable_date, :start_time, :end_time, :max_reserve_num)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue('reservable_date', $reservable_date, PDO::PARAM_STR);
        $stmt->bindValue('start_time', $start_time, PDO::PARAM_STR);
        $stmt->bindValue('end_time', $end_time, PDO::PARAM_STR);
        $stmt->bindValue('max_reserve_num', (int)$max_reserve_num, PDO::PARAM_INT);
        $stmt->execute();
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

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">

    <!-- Original CSS -->
    <link href="/css/style.css" rel="stylesheet">

    <title>設定</title>
</head>

<body>

    <header class="navbar">
        <div class="container-fluid">
            <a href="../index.php" class="navbar-brand">SAMPLE SHOP</a>
            <form class="d-flex">
                <a href="/admin/reserve_list.php" class="mx-3"><i class="bi bi-list-task nav-icon"></i></a>
                <a href="/admin/setting.php"><i class="bi bi-gear nav-icon"></i></a>

            </form>
        </div>
    </header>

    <h1>設定</h1>

    <form class="card" method="post">
        <div class="card-body">
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">予約可能日</label>
                <?= arrayToSelect('reservable_date', $reservable_date_array, $shop['reservable_date']) ?>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">営業時間（予約可能時間）</label>
                <div class="row">
                    <div class="col-5">
                        <?= arrayToSelect('start_time', $start_time_array, $shop['start_time']) ?>
                    </div>
                    <div class="col-2 text-center pt-2">
                        <span>〜</span>
                    </div>
                    <div class="col-5">
                        <?= arrayToSelect('end_time', $end_time_array,  $shop['end_time']) ?>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">1時間あたりの予約上限人数</label>
                <?= arrayToSelect('max_reserve_num', $max_reserve_num_array,  $shop['max_reserve_num']) ?>
            </div>

            <div class="d-grid gap-2">
                <button class="btn btn-primary rounded-pill" type="submit">登録</button>
            </div>
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
