<?php
require_once '../config/config.php';
require_once '../functions.php';

session_start();

if (isset($_SESSION['USER'])) {
    redirect('setting.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login_id = $_POST['login_id'];
    $login_password = $_POST['login_password'];

    $pdo = connect_db();

    $sql = 'SELECT login_id, login_password FROM shop WHERE login_id = :login_id AND login_password = :login_password LIMIT 1';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue('login_id', $login_id, PDO::PARAM_STR);
    $stmt->bindValue('login_password', $login_password, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['USER'] = $user;
        var_dump($user);

        redirect('setting.php');
    } else {
        $err['password'] = '認証に失敗しました';
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

    <!-- Original CSS -->
    <link href="/css/style.css" rel="stylesheet">

    <title>管理ログイン画面</title>
</head>

<body>
    <header>SAMPLE SHOP</header>

    <h1>ログイン</h1>

    <form class="card text-center" method="post">
        <div class="card-body">
            <div class="mb-3">
                <input type="text" class="form-control rounded-pill py-3" name="login_id" placeholder="ID">
            </div>
            <div class="mb-3">
                <input type="password" class="form-control rounded-pill py-3" name="login_password" placeholder="パスワード">
            </div>

            <div class="d-grid gap-2">
                <button class="btn btn-primary rounded-pill" type="submit">ログイン</button>
            </div>
        </div>
    </form>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
