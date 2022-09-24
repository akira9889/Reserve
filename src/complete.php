<?php
$path = '';
$page_title = '予約内容確認';
?>
<!doctype html>
<html lang="ja">

<?php include('templates/head_tag.php'); ?>

<body>
    <?php include('templates/header.php'); ?>
    <div class="card text-center">
        <div class="card-body">
            <i class="bi bi-check-lg conmplete-icon"></i>
            <h2 class="card-title">予約が完了しました。</h2>
            <p>予約確認メールを送信しましたので、ご確認くださいませ。</p>
            <div class="d-grid gap-2  my-3">
                <a class="btn btn-primary rounded-pill my-3" href="/">TOPに戻る</a>
            </div>
        </div>

        <!-- Option 1: Bootstrap Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
