<?php
require_once '../config/config.php';
require_once '../functions.php';

//予約リストデータを取得
if (isset($_GET['Y'])) {
    $yyyy = $_GET['Y'];
} else {
    $yyyy = date('Y');
}

if (isset($_GET['m'])) {
    $mm = $_GET['m'];
} else {
    $mm = date('m');
}

$target_yyyymm = $yyyy . '-' . $mm;

//データーベース接続
$pdo = connect_db();
//データベースから指定した予約リストを取得
$sql = "SELECT * FROM reserve WHERE DATE_FORMAT(reserve_date, '%Y-%m') = :reserve_date ORDER BY reserve_date, reserve_time ASC";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':reserve_date', $target_yyyymm, PDO::PARAM_STR);
$stmt->execute();
$reserve_list = $stmt->fetchAll();

// var_dump($reserve_list);
// exit;

//データベース切断
$stmt = null;
$pdo = null;

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

    <title>予約リスト</title>
</head>

<body>

    <header class="navbar">
        <div class="container-fluid">
            <div class="navbar-brand">SAMPLE SHOP</div>
            <form class="d-flex">
                <a href="/admin/reserve_list.php" class="mx-3"><i class="bi bi-list-task nav-icon"></i></a>
                <a href="/admin/setting.php"><i class="bi bi-gear nav-icon"></i></a>

            </form>
        </div>
    </header>

    <h1>予約リスト</h1>

    <form class="row m-3">
        <div class="col">
            <select class="form-select" name="Y" onchange="submit(this.form)">
                <option value="2022">2022年</option>
                <?php for ($i = 1; $i < 12; $i++) : ?>
                    <?php $target_yyyy = date('Y', strtotime(date('Y') . "+{$i}year")); ?>
                    <option value="<?= $target_yyyy?>" <?php if ($yyyy == $target_yyyy) echo 'selected' ?>>
                        <?= date('Y', strtotime(date($target_yyyy . '-m'))) . '年' ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col">
            <select class="form-select" name="m" onchange="submit(this.form)">
                <option value="<?= date('m') ?>"><?= date('n') . '月' ?></option>
                <?php for ($i = 1; $i < 12; $i++) : ?>
                    <?php $target_mm = date('m', strtotime(date('Y-m') . "+{$i}month")); ?>
                    <option value="<?= $target_mm?>" <?php if ($mm == $target_mm) echo 'selected' ?>>
                        <?= date('n', strtotime(date('Y-' . $target_mm))) . '月' ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
    </form>

    <table class="table reserve_list_table">
        <tbody>
            <?php foreach ($reserve_list as $reserve) : ?>
                <tr>
                    <td><?= time_format_dw($reserve['reserve_date']) ?></td>
                    <td><?= date('H:i', strtotime($reserve['reserve_time'])) ?></td>
                    <td>
                        <?= $reserve['name'] ?>　<?= $reserve['reserve_num'] . '名' ?> <br>
                        <?= $reserve['email'] ?> <br>
                        <?= $reserve['tel'] ?> <br>
                        <?= $reserve['comment'] ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

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
