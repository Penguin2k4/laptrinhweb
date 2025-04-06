<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quản lý sản phẩm</title>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<style>
    .btn-logout {
        background-color: #e74c3c;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
    }
    .btn-logout:hover {
        background-color: #c0392b;
    }
    .btn-login {
        background-color: #3498db;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
    }
    .btn-login:hover {
        background-color: #2980b9;
    }
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
<a class="navbar-brand" href="#">Quản lý sản phẩm</a>

<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
<span class="navbar-toggler-icon"></span>
</button>
<div class="collapse navbar-collapse" id="navbarNav">
<ul class="navbar-nav">
<li class="nav-item">
<a class="nav-link" href="/webbanhang/">Danh sách sản phẩm</a>
</li>
<li class="nav-item">
<a class="nav-link" href="/webbanhang/Product/add">Thêm sản phẩm</a>
</li>
</ul>
<ul class="navbar-nav ml-auto">
<?php if (isset($_SESSION['user_role'])): ?>
    <li class="nav-item">
        <span class="navbar-text">Xin chào, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Guest', ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars($_SESSION['user_role'], ENT_QUOTES, 'UTF-8'); ?>)</span>
    </li>
    <li class="nav-item">
        <a class="nav-link btn-logout" href="/webbanhang/Auth/logout">Đăng xuất</a>
    </li>
<?php else: ?>
    <li class="nav-item">
        <a class="nav-link btn-login" href="/webbanhang/Auth/login">Đăng nhập</a>
    </li>
<?php endif; ?>
</ul>
<form class="form-inline my-2 my-lg-0" method="GET" action="/webbanhang/Product/search">
    <input class="form-control mr-sm-2" type="search" placeholder="Tìm kiếm" aria-label="Search" name="query">
    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Tìm kiếm</button>
</form>
</div>
</nav>
<div class="container mt-4">
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>