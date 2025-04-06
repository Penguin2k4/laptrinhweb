<?php
require_once('app/config/database.php');
require_once('app/helpers/SessionHelper.php');

class AuthController
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    public function register()
    {
        include 'app/views/auth/register.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $role = 'user'; // Set role to 'user' by default

            $query = "INSERT INTO account (username, password, role) VALUES (:username, :password, :role)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':role', $role);

            if ($stmt->execute()) {
                header('Location: /webbanhang/Auth/login');
            } else {
                echo "Đăng ký thất bại.";
            }
        }
    }

    public function login()
    {
        include 'app/views/auth/login.php';
    }

    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $query = "SELECT * FROM account WHERE username = :username";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_OBJ);

            if ($user && password_verify($password, $user->password)) {
                SessionHelper::startSession();
                $_SESSION['user_id'] = $user->id;
                $_SESSION['username'] = $user->username; // Store username in session
                $_SESSION['user_role'] = $user->role;
                header('Location: /webbanhang/');
            } else {
                echo "Tên đăng nhập hoặc mật khẩu không đúng.";
            }
        }
    }

    public function logout()
    {
        SessionHelper::startSession();
        session_destroy();
        header('Location: /webbanhang/Auth/login');
    }
}
?>
