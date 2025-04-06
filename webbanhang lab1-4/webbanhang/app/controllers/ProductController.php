<?php
// Require SessionHelper and other necessary files
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/helpers/SessionHelper.php');

class ProductController
{
    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function add()
    {
        // Check if the user has the admin role
        SessionHelper::checkAdmin();
        $categories = (new CategoryModel($this->db))->getCategories();
        include_once 'app/views/product/add.php';
    }

    public function save()
    {
        // Check if the user has the admin role
        SessionHelper::checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') 
        {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) 
            {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = "";
            }
            $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);
            if (is_array($result)) 
            {
                $errors = $result;
                $categories = (new CategoryModel($this->db))->getCategories();
                include 'app/views/product/add.php';
            } else {
                header('Location: /webbanhang/Product');
            }
        }
    }
    
    public function edit($id)
    {
        // Check if the user has the admin role
        SessionHelper::checkAdmin();
        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();
        if ($product) 
        {
            include 'app/views/product/edit.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function update()
    {
        // Check if the user has the admin role
        SessionHelper::checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = $_POST['existing_image'];
            }
            $edit = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);
            if ($edit) {
                header('Location: /webbanhang/Product');
            } else {
                echo "Đã xảy ra lỗi khi lưu sản phẩm.";
            }
        }
    }

    public function delete($id)
    {
        // Check if the user has the admin role
        SessionHelper::checkAdmin();
        if ($this->productModel->deleteProduct($id)) {
            header('Location: /webbanhang/Product');
        } else {
            echo "Đã xảy ra lỗi khi xóa sản phẩm.";
        }
    }

    private function uploadImage($file)
    {
        $target_dir = "uploads/";
        // Kiểm tra và tạo thư mục nếu chưa tồn tại
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Kiểm tra xem file có phải là hình ảnh không
        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            throw new Exception("File không phải là hình ảnh.");
        }
        // Kiểm tra kích thước file (10 MB = 10 * 1024 * 1024 bytes)
        if ($file["size"] > 10 * 1024 * 1024) {
            throw new Exception("Hình ảnh có kích thước quá lớn.");
        }
        // Chỉ cho phép một số định dạng hình ảnh nhất định
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            throw new Exception("Chỉ cho phép các định dạng JPG, JPEG, PNG và GIF.");
        }
        // Lưu file
        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
        }
        return $target_file;
    }

    public function addToCart($id)
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) { 
            echo "Không tìm thấy sản phẩm.";
            return;
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image
            ];
        }
        header('Location: /webbanhang/Product/cart');
    }

    public function cart()
    {
        $banList = $this->getBanList(); 
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        $totalPrice = 0;

        // Tính tổng tiền
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }
        $so_ban = $_SESSION['so_ban'] ?? 'Chưa chọn'; // Lấy số bàn từ session, mặc định là 'Chưa chọn'
        include 'app/views/product/cart.php';
    }

    public function checkout()
    {
        include 'app/views/product/checkout.php';
    }

    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Kết nối với MySQL bằng PDO
                $pdo = new PDO("mysql:host=localhost;dbname=my_store;charset=utf8mb4", "root", "");
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Lấy số bàn từ session
                $so_ban = $_SESSION['so_ban'] ?? null;
                if (!$so_ban) {
                    die("Lỗi: Chưa chọn số bàn.");
                }

                // Kiểm tra giỏ hàng
                if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                    die("Giỏ hàng trống.");
                }

                $cart = $_SESSION['cart'];
                $totalPrice = 0;

                // Tính tổng tiền
                foreach ($cart as $item) {
                    $totalPrice += $item['price'] * $item['quantity'];
                }

                // Bắt đầu giao dịch
                $pdo->beginTransaction();

                // 1️⃣ Lưu thông tin đơn hàng vào bảng `orders`
                $stmt = $pdo->prepare("INSERT INTO orders (table_number, order_date, total_amount) VALUES (?, NOW(), ?)");
                $stmt->execute([$so_ban, $totalPrice]);
                $order_id = $pdo->lastInsertId();

                // 2️⃣ Lưu từng sản phẩm vào bảng `order_items`
                $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                foreach ($cart as $product_id => $item) {
                    $stmt->execute([$order_id, $product_id, $item['quantity'], $item['price']]);
                }

                // 3️⃣ Cập nhật trạng thái bàn trong bảng `ban`
                $stmt = $pdo->prepare("UPDATE ban SET trang_thai = 'Đang phục vụ' WHERE so_ban = ?");
                $stmt->execute([$so_ban]);

                // Hoàn tất giao dịch
                $pdo->commit();

                // Xóa giỏ hàng
                unset($_SESSION['cart']);

                header('Location: /webbanhang/Product/orderConfirmation');
                
            } catch (Exception $e) {
                // Nếu có lỗi, hủy giao dịch
                $pdo->rollBack();
                echo "❌ Lỗi khi xử lý đơn hàng: " . $e->getMessage();
            }
        } else {
            echo "🚫 Không được phép truy cập trực tiếp.";
        }
    }

    public function orderConfirmation()
    {
        include 'app/views/product/orderConfirmation.php';
    }

    public function removeFromCart($id)
    {
        session_start(); // Đảm bảo session được khởi động

        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]); // Xóa sản phẩm khỏi giỏ hàng
            header('Location: /webbanhang/Product/cart'); // Chuyển hướng về trang giỏ hàng
            exit();
        } else {
            echo "Sản phẩm không tồn tại trong giỏ hàng.";
        }
    }

    public function updateCartQuantity() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['quantity'])) {
            $id = $_POST['id'];
            $quantity = intval($_POST['quantity']);
            
            if ($quantity > 0 && isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity'] = $quantity;
            }
        }
        header('Location: /webbanhang/Product/cart');
        exit();
    }

    public function getBanList()
    {
        $query = "SELECT * FROM ban";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ); // Trả về mảng các object ban
    }

    public function showSelectBanFromCart()
    {
        include 'app/views/product/select_ban_from_cart.php'; // Tạo một view mới để chọn số bàn
    }

    public function setSoBan()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_SESSION['so_ban'] = $_POST['so_ban'];
            header('Location: /webbanhang/Product/cart');  // Chuyển hướng về trang giỏ hàng
        }
    }

    public function invoice() {
        // Tạo số phiếu ngẫu nhiên gồm 6 chữ số
        $bill_id = 'HD' . mt_rand(100000, 999999);

        // Lưu vào session để hiển thị trên giao diện
        $_SESSION['bill_id'] = $bill_id;

        include 'app/views/Product/invoice.php';
    }

    public function search()
    {
        $query = $_GET['query'] ?? '';
        $products = $this->productModel->searchProducts($query);
        
        if (count($products) == 1) {
            $product = reset($products); // Get the single product
            header('Location: /webbanhang/Product/show/' . $product->id);
            exit();
        }

        include 'app/views/product/list.php';
    }
}
?>