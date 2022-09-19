<?php
function arrayToSelect($inputName, $srcArray, $err, $selectedIndex = "")
{
    if (isset($err)) {
        $temphtml = "<select class=\"form-select is-invalid\" name=\"{$inputName}\">" . PHP_EOL;
    } else {
        $temphtml = "<select class=\"form-select\" name=\"{$inputName}\">" . PHP_EOL;
    }

    foreach ($srcArray as $key => $val) {
        if ($key == $selectedIndex) {
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
