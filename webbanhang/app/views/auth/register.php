<?php include 'app/views/shares/header.php'; ?>
<h1>Đăng ký</h1>
<form method="POST" action="/webbanhang/Auth/store">
    <div class="form-group">
        <label for="username">Tên đăng nhập:</label>
        <input type="text" id="username" name="username" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="password">Mật khẩu:</label>
        <input type="password" id="password" name="password" class="form-control" required>
    </div>
    <!-- Remove the role selection -->
    <button type="submit" class="btn btn-primary">Đăng ký</button>
</form>
<a href="/webbanhang/Auth/login" class="btn btn-secondary mt-2">Đăng nhập</a>
<?php include 'app/views/shares/footer.php'; ?>
