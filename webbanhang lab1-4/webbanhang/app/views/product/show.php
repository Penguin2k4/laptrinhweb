<?php include 'app/views/shares/header.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Chi tiết sản phẩm</title>
    <link rel="stylesheet" type="text/css" href="/webbanhang/public/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Chi tiết sản phẩm</h1>

        <?php if ($product): ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></h5>
                    <?php if (isset($product->image_url) && $product->image_url !== ''): ?>
                        <img src="/webbanhang/<?php echo htmlspecialchars($product->image_url, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" style="width: 100px; height: auto;">
                    <?php else: ?>
                        <p>Không có ảnh</p>
                    <?php endif; ?>
                    <p class="card-text"><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="card-text">Giá: <?php echo htmlspecialchars($product->price, ENT_QUOTES, 'UTF-8'); ?></p>

                    <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-warning">Sửa</a>
                    <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
                </div>
            </div>
        <?php else: ?>
            <p>Không tìm thấy sản phẩm.</p>
        <?php endif; ?>

        <a href="/webbanhang" class="btn btn-primary">Quay lại danh sách</a>
    </div>

    <?php include 'app/views/shares/footer.php'; ?>
</body>
</html>