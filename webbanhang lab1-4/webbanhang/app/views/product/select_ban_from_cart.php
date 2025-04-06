<?php include 'app/views/shares/header.php'; ?>
<div class="container">
    <h1>Chọn số bàn</h1>
    <form action="/webbanhang/Product/setSoBan" method="POST">
        <label for="so_ban">Số bàn:</label>
        <select name="so_ban" id="so_ban" required>
            <option value="" disabled selected>Chọn số bàn...</option>
            <?php for ($i = 1; $i <= 20; $i++): ?>
                <option value="<?php echo $i; ?>" <?php echo (isset($_SESSION['so_ban']) && $_SESSION['so_ban'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
            <?php endfor; ?>
        </select>
        <button type="submit">Xác nhận</button>
        <a href="/webbanhang/Product/cart" class="btn btn-secondary">Hủy</a> <!-- Nút hủy -->
    </form>
</div>
<?php include 'app/views/shares/footer.php'; ?>