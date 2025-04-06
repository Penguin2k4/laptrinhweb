<?php include 'app/views/shares/header.php'; ?>
<h1>Xác nhận đơn hàng</h1>

<?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Giá</th>
                <th>Tổng cộng</th>
            </tr>
        </thead>
        <tbody>
            <?php $totalPrice = 0; ?>
            <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo number_format($item['price'], 0, ',', '.'); ?> VND</td>
                    <td><?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?> VND</td>
                </tr>
                <?php $totalPrice += $item['price'] * $item['quantity']; ?>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="text-right"><strong>Tổng tiền:</strong></td>
                <td><?php echo number_format($totalPrice, 0, ',', '.'); ?> VND</td>
            </tr>
        </tfoot>
    </table>

    <?php if (isset($_SESSION['so_ban'])): ?>
        <p><strong>Số bàn:</strong> <?php echo htmlspecialchars($_SESSION['so_ban'], ENT_QUOTES, 'UTF-8'); ?></p>
    <?php else: ?>
        <p><strong>Số bàn:</strong> Chưa chọn</p>
    <?php endif; ?>

    
<?php else: ?>
    <p>Giỏ hàng của bạn đang trống.</p>
<?php endif; ?>
<a href="/webbanhang/Product/invoice" class="btn btn-warning mt-2">🧾 Xuất Bill</a>
<a href="/webbanhang/Product/cart" class="btn btn-secondary mt-2">Quay lại giỏ hàng</a>
<?php include 'app/views/shares/footer.php'; ?>