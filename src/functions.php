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
