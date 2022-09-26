<?php
require_once '../config/config.php';
require_once '../functions.php';

session_start();

if (!isset($_SESSION['USER'])) {
    redirect('/admin/login.php');
}

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

if ($target_yyyymm < date('Y-m')) {
    $mm = date('m');
    $target_yyyymm = $yyyy . '-' . $mm;
}

//データーベース接続
$pdo = connect_db();
//データベースから指定した日時の予約リストを取得
$sql = "SELECT * FROM reserve WHERE DATE_FORMAT(reserve_date, '%Y-%m') = :reserve_date ORDER BY reserve_date, reserve_time ASC";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':reserve_date', $target_yyyymm, PDO::PARAM_STR);
$stmt->execute();
$reserve_list = $stmt->fetchAll();

//データベース切断
$stmt = null;
$pdo = null;

$dirs = explode('/', __DIR__);
$thisdir = array_pop($dirs);

$path = '../';
$page_title = '予約リスト';
?>
<!doctype html>
<html lang="ja">

<?php include('../templates/head_tag.php'); ?>

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

    <form id="reserve-form" class="row m-3">
        <div class="col">
            <select id="form-year" class="form-select" name="Y">
                <?php for ($i = 0; $i < 5; $i++) : ?>
                    <?php $target_yyyy = date('Y', strtotime(date('Y') . "+{$i}year")); ?>
                    <option value="<?= $target_yyyy ?>" <?php if ($yyyy == $target_yyyy) echo 'selected' ?>>
                        <?= date('Y', strtotime(date($target_yyyy . '-m'))) . '年' ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col">
            <select id="form-month" class="form-select" name="m" onchange="submit(this.form)">
                <?php
                if ($yyyy == date('Y')) {
                    $month_count = 12 - ltrim(date('m'));
                } else {
                    $month_count = 11;
                }
                ?>
                <?php for ($i = 0; $i <= $month_count; $i++) : ?>
                    <?php
                    if ($yyyy == date('Y')) {
                        $target_mm = date('m', strtotime(date('Y-m') . "+{$i}month"));
                    } else {
                        $target_mm = date('m', strtotime(date('Y-01') . "+{$i}month"));
                    }
                    ?>
                    <option value="<?= $target_mm ?>" <?php if ($mm == $target_mm) echo 'selected' ?>>
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

    <?php include('../templates/footer.php'); ?>

    <script>
        // NUM=値 LEN=桁数
        function zeroPadding(NUM, LEN) {
            return (Array(LEN).join('0') + NUM).slice(-LEN);
        }

        //年度を変えた時、変えた年の月が今月より前だったら、月のフォーム値を今月に変える
        let now = new Date();
        let current_year = now.getFullYear();
        let current_month = now.getMonth() + 1;
        let previous_year;

        $("#form-year").on('focus', function() {
            previous_year = Number(this.value);
            previous_month = Number($('#form-month').val());
        }).change(function() {
            target_year = Number($('#form-year').val());
            if (previous_year > target_year && target_year == current_year) {
                if (previous_month < current_month) {
                    $('#form-month').val(zeroPadding(current_month, 2));
                    console.log($('#form-month').val());
                }
            }
            $('#reserve-form').submit();
        });
    </script>
</body>

</html>
