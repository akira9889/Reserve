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
