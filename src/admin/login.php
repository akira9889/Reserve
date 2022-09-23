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

    $err = array();

    if (!$login_id) {
        $err['login_id'] = 'ログインIDを入力してください';
    } elseif (mb_strlen($login_id, 'utf-8') > 20) {
        $err['login_id'] = 'ログインIDが長すぎます';
    }

    if (!$login_password) {
        $err['login_password'] = 'パスワードを入力してください';
    }

    if (empty($err)) {

        $pdo = connect_db();

        $sql = 'SELECT login_id, login_password FROM shop WHERE login_id = :login_id AND login_password = :login_password LIMIT 1';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue('login_id', $login_id, PDO::PARAM_STR);
        $stmt->bindValue('login_password', $login_password, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user) {
            $_SESSION['USER'] = $user;
            redirect('setting.php');
            exit;
        } else {
            $err['login_password'] = '認証に失敗しました';
        }
    }
}
$dirs = explode('/', __DIR__);
$thisdir = array_pop($dirs);

$path = '../';
$page_title = 'ログイン';
?>
<!doctype html>
<html lang="ja">

<?php include('../templates/head_tag.php'); ?>

<body>
    <header>SAMPLE SHOP</header>

    <h1>ログイン</h1>

    <form class="card text-center" method="post">
        <div class="card-body">
            <div class="mb-3">
                <input type="text" class="form-control rounded-pill py-3 <?php if (isset($err['login_id'])) echo 'is-invalid'; ?>" name="login_id" placeholder="ID">
                <div class="invalid-feedback"><?= $err['login_id'] ?></div>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control rounded-pill py-3 <?php if (isset($err['login_password'])) echo 'is-invalid'; ?>" name="login_password" placeholder="パスワード">
                <div class="invalid-feedback"><?= $err['login_password'] ?></div>
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
