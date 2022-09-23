<?php if ($thisdir == 'admin'): ?>
    <header class="navbar">
        <div class="container-fluid">
            <div class="navbar-brand">SAMPLE SHOP</div>
            <form class="d-flex">
                <a href="/admin/reserve_list.php" class="mx-3"><i class="bi bi-list-task nav-icon"></i></a>
                <a href="/admin/setting.php"><i class="bi bi-gear nav-icon"></i></a>

            </form>
        </div>
    </header>
<?php else: ?>
    <header>SAMPLE SHOP</header>
<?php endif; ?>

<h1><?= $page_title ?></h1>
