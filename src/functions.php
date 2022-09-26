<?php
function arrayToSelect($inputName, $srcArray, $errs, $selectedVal = "")
{
    foreach ($errs as $err) {
        if (isset($err)) {
            $temphtml = "<select id=\"{$inputName}\" class=\"form-select is-invalid\" name=\"{$inputName}\">" . PHP_EOL;
        } else {
            $temphtml = "<select id=\"{$inputName}\" class=\"form-select\" name=\"{$inputName}\">" . PHP_EOL;
        }
    }

    foreach ($srcArray as $key => $val) {
        if ($key == $selectedVal) {
            $selectedText = "selected";
        } else {
            $selectedText = "";
        }
        $temphtml .= "<option value=\"{$key}\"{$selectedText}>{$val}</option>" . PHP_EOL;
    }

    $temphtml .= "</select>";

    return $temphtml;

}

function connect_db()
{
    $param = 'mysql:dbname=' . DB_NAME . ';host=' . DB_HOST . '';
    $pdo = new PDO($param, DB_USER, DB_PASSWORD);
    $pdo->query('SET NAMES utf8;');
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
}

function time_format_dw($date)
{
    $format_date = NULL;
    $week = array('日', '月', '火', '水', '木', '金', '土');

    if ($date) {
        $format_date = date('n/j(' . $week[date('w', strtotime($date))] . ')', strtotime($date));
    }

    return $format_date;
}

//時間の形式チェックを行う
function check_time_format($time)
{
    if (preg_match('/^([01]?[0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/', $time)) {
        return true;
    } else {
        return false;
    }
}

//HTMLエスケープ処理（XSS対策）
function h($original_str)
{
    return htmlspecialchars($original_str, ENT_QUOTES, 'UTF-8');
}

function redirect($path)
{
    unset($pdo);
    header('Location:' . $path);
    exit;
}

function array_group_by(array $items, $keyName): array
{
    $group = [];

    foreach ($items as $item) {
        $key = $item[$keyName];
        if (array_key_exists($key, $group)) {
            $group[$key][] = $item;
        } else {
            $group[$key] = [$item];
        }
    }

    return $group;
}

function getTotalReserveNumOfDate($date, $pdo) {
//reserveテーブルから$dateの時間と時間ごとの予約合計人数を取得
    $target_date = $date;

    $sql = "SELECT DATE_FORMAT(reserve_date_time, '%Y-%m-%d %H:%i') AS date_time,reserve_num
            FROM reserve
            WHERE DATE_FORMAT(reserve_date, '%Y-%m-%d') = :reserve_date
            ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':reserve_date', $target_date, PDO::PARAM_STR);
    $stmt->execute();

    $reserve_list = $stmt->fetchAll();

    $reserve_list = array_group_by($reserve_list,'date_time');

    $total_reserve_num = 0;
    foreach ($reserve_list as $date_time => $array) {
        $total_reserve_num = array_sum(array_column($array, 'reserve_num'));
        $reserve_list[$date_time] = $total_reserve_num;
    }

    return $reserve_list;
}

function createOption ($pdo) {
    //shopテーブルから最大予約人数,営業時間を取得
    $sql = "SELECT start_time, end_time, max_reserve_num from shop";
    $stmt = $pdo->query($sql);
    $shop = $stmt->fetch();

    $start_time = $shop['start_time'];
    $end_time = $shop['end_time'];
    $max_reserve_num = $shop['max_reserve_num'];

    //reserveテーブルから選択された時間と時間ごとの予約合計人数を取得
    $reserve_list = getTotalReserveNumOfDate($_POST['date'], $pdo);

    $reserve_time_array = [];

    if (date('Y-m-d H:i:s', strtotime($start_time)) <= date('Y-m-d H:i:s', strtotime($end_time))) {
        for (
            $i = date('Y-m-d H:i:s', strtotime($start_time));
            $i <= date('Y-m-d H:i:s', strtotime($end_time));
            $i = date('Y-m-d H:i:s', strtotime($i . '+1 hours'))
        ) {
            $reserve_time_array[] = date('G:i', strtotime($i));
        }
    } else {
        for (
            $i = date('Y-m-d H:i:s', strtotime('00:00:00'));
            $i <= date('Y-m-d H:i:s', strtotime($end_time));
            $i = date('Y-m-d H:i:s', strtotime($i . '+1 hours'))
        ) {
            $reserve_time_array[] = date('H:i', strtotime($i));
        }

        for (
            $i = date('Y-m-d H:i:s', strtotime($start_time));
            $i <= date('Y-m-d H:i:s', strtotime('23:00:00'));
            $i = date('Y-m-d H:i:s', strtotime($i . '+1 hours'))
        ) {
            $reserve_time_array[] = date('H:i', strtotime($i));
        }
    }

    $reserve_time_array = array_combine($reserve_time_array, $reserve_time_array);

//shopテーブルから最大予約人数とreserveテーブルから選択された日時の合計予約人数の差が0以下なら、その時間のoptionの生成だけを除外
    foreach ($reserve_list as $date_time => $total_reserve_num) {
        if ($max_reserve_num - $total_reserve_num <= 0) {
            $key = date('H:i', strtotime($date_time));
        }
        unset($reserve_time_array[$key]);
    }

    $temphtml = '';
    foreach ($reserve_time_array as $key => $selectedVal) {
        if ($key == $selectedVal) {
            $selectedText = "selected";
        } else {
            $selectedText = "";
        }
        $temphtml .= "<option value=\"{$key}\"{$selectedText}>{$selectedVal}</option>" . PHP_EOL;
    }

    echo $temphtml;
}
