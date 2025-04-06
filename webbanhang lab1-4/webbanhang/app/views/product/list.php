<?php include 'app/views/shares/header.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Danh sách sản phẩm</title>
    <link rel="stylesheet" type="text/css" href="/webbanhang/public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .btn i {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <h1>Danh sách sản phẩm</h1>
    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
        <a href="/webbanhang/Product/add" class="btn btn-success mb-2">Thêm sản phẩm mới nè</a>
    <?php endif; ?>

    <div class="product-list">
        <?php foreach ($products as $product): ?>
            <div class="product-item">
                 <img src="<?php echo htmlspecialchars($product-> image_url,
                  ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" style="width: 100px; height: auto;">

                <a href="/webbanhang/Product/show/<?php echo $product->id; ?>">
                    <h2><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></h2>
                </a>
                <p><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></p>
                <p>Giá: <?php echo htmlspecialchars($product->price, ENT_QUOTES, 'UTF-8'); ?></p>
                <p><strong>Danh mục:</strong> <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?></p>

                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Sửa
                    </a>
                    <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                        <i class="fas fa-trash-alt"></i> Xóa
                    </a>
                <?php elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'user'): ?>
                    <a href="/webbanhang/Product/addToCart/<?php echo $product->id; ?>" 
                                   class="btn btn-primary btn-sm float-end">
                                    🛒 Gọi món
                    </a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php include 'app/views/shares/footer.php'; ?>
</body>
</html>