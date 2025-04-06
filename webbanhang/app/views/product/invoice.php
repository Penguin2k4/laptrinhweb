<?php include 'app/views/shares/header.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa Đơn Thanh Toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Courier New', Courier, monospace;
        }
        .bill-container {
            width: 350px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            text-align: center;
        }
        .bill-header h2 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        .table {
            font-size: 14px;
        }
        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }
        .footer-text {
            font-size: 14px;
            font-style: italic;
            margin-top: 10px;
        }
        .btn-back {
            display: block;
            margin-top: 15px;
            text-decoration: none;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="bill-container">
        <div class="bill-header">
            <h2>Cafe Nét Xưa</h2>
            <p></p>
            <p>ĐT: 01647.266.798</p>
        </div>
        
        <hr>
        <p><strong>HÓA ĐƠN THANH TOÁN</strong></p>
        Số phiếu: <strong><?= htmlspecialchars($_SESSION['bill_id'] ?? 'N/A') ?></strong> | Ngày: <strong><?= date('d/m/Y H:i') ?></strong></p>
        <?php if (isset($_SESSION['so_ban'])): ?>
            <p><strong>Số bàn:</strong> <?php echo htmlspecialchars($_SESSION['so_ban'], ENT_QUOTES, 'UTF-8'); ?></p>
        <?php else: ?>
            <p><strong>Số bàn:</strong> Chưa chọn</p>
        <?php endif; ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tên hàng</th>
                    <th>SL</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php $totalPrice = 0; ?>
                <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?= $item['quantity']; ?></td>
                        <td><?= number_format($item['price'], 0, ',', '.'); ?> VNĐ</td>
                        <td><strong><?= number_format($item['quantity'] * $item['price'], 0, ',', '.'); ?> VNĐ</strong></td>
                    </tr>
                    <?php $totalPrice += $item['quantity'] * $item['price']; ?>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p class="total">Tổng tiền: <?= number_format($totalPrice, 0, ',', '.'); ?> VNĐ</p>
        <p class="footer-text">Cảm ơn Quý khách, hẹn gặp lại!</p>

        <button onclick="window.print()" class="btn btn-success w-100">🖨 In hóa đơn</button>
        <form method="POST" action="/webbanhang/Product/processCheckout">
            <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Xác nhận và lưu đơn hàng</button>
        </form>

        <a href="/webbanhang/Product/checkout" class="btn btn-primary w-100 btn-back">Quay lại</a>
    </div>
    <?php include 'app/views/shares/footer.php'; ?>
</body>
</html>