<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/utils/JWTHandler.php'); // Include JWTHandler

class ProductApiController
{
    private $productModel;
    private $db;
    private $jwtHandler; // Add JWTHandler property

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->jwtHandler = new JWTHandler(); // Initialize JWTHandler
    }

    private function authenticate()
    {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            $arr = explode(" ", $authHeader);
            $jwt = $arr[1] ?? null;
            if ($jwt) {
                $decoded = $this->jwtHandler->decode($jwt);
                return $decoded ? true : false;
            }
        }
        return false;
    }

    // Lấy danh sách sản phẩm
    public function index()
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $products = $this->productModel->getProducts();
            echo json_encode($products);
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }

    // Lấy thông tin sản phẩm theo ID
    public function show($id)
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $product = $this->productModel->getProductById($id);
            if ($product) {
                echo json_encode($product);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Product not found']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }

    // Thêm sản phẩm mới
    public function store()
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $data = json_decode(file_get_contents("php://input"), true);
            $name = $data['name'] ?? '';
            $description = $data['description'] ?? '';
            $price = $data['price'] ?? '';
            $category_id = $data['category_id'] ?? null;
            $result = $this->productModel->addProduct($name, $description, $price, $category_id);
            if (is_array($result)) {
                http_response_code(400);
                echo json_encode(['errors' => $result]);
            } else {
                http_response_code(201);
                echo json_encode(['message' => 'Product created successfully']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }

    // Cập nhật sản phẩm theo ID
    public function update($id)
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $data = json_decode(file_get_contents("php://input"), true);
            $name = $data['name'] ?? '';
            $description = $data['description'] ?? '';
            $price = $data['price'] ?? '';
            $category_id = $data['category_id'] ?? null;
            $result = $this->productModel->updateProduct($id, $name, $description, $price, $category_id);
            if ($result) {
                echo json_encode(['message' => 'Product updated successfully']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Product update failed']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }

    // Cập nhật một số trường của sản phẩm theo ID
    public function patch($id)
    {
        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $data = json_decode(file_get_contents("php://input"), true);

            // Fetch the existing product
            $product = $this->productModel->getProductById($id);
            if (!$product) {
                http_response_code(404);
                echo json_encode(['message' => 'Product not found']);
                return;
            }

            // Update only the provided fields
            $name = $data['name'] ?? $product->name;
            $description = $data['description'] ?? $product->description;
            $price = $data['price'] ?? $product->price;
            $category_id = $data['category_id'] ?? $product->category_id;

            $result = $this->productModel->updateProduct($id, $name, $description, $price, $category_id);
            if ($result) {
                echo json_encode(['message' => 'Product updated successfully']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Product update failed']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }

    // Xóa sản phẩm theo ID
    public function destroy($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['message' => 'Method Not Allowed']);
            return;
        }

        if ($this->authenticate()) {
            header('Content-Type: application/json');
            $result = $this->productModel->deleteProduct($id);
            if ($result) {
                echo json_encode(['message' => 'Product deleted successfully']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Product deletion failed']);
            }
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
        }
    }
}
?>