<?php
function arrayToSelect($inputName, $srcArray, $selectedIndex = "")
{
    $temphtml = "<select class=\"form-select\" name=\"{$inputName}\">" . PHP_EOL;

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

function date_format_dw (string $date) {
    $yyyymmdd =  substr_replace(substr_replace($date, '-', -2, 0), '-', 4, 0);
    return date('n/j', strtotime($yyyymmdd));
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


