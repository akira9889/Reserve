<?php
require_once('../functions.php');

try {
    session_start();

    $_SESSION = array();

    session_destroy();

    redirect('login.php');
} catch (Exception $e) {
    redirect('/error.php');
}
