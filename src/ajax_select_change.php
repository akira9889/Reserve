<?php
require_once 'config/config.php';
require_once 'functions.php';

$pdo = connect_db();
$temphtml = createOption($pdo);

echo $temphtml;
