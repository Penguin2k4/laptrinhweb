<?php include 'app/views/shares/header.php'; ?>
<h1>Đăng nhập</h1>
<form method="POST" action="/webbanhang/Auth/authenticate">
    <div class="form-group">
        <label for="username">Tên đăng nhập:</label>
        <input type="text" id="username" name="username" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="password">Mật khẩu:</label>
        <input type="password" id="password" name="password" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Đăng nhập</button>
</form>
<a href="/webbanhang/Auth/register" class="btn btn-secondary mt-2">Đăng ký</a>
<?php include 'app/views/shares/footer.php'; ?>
