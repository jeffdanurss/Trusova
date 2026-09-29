<?php
class AuthController {
    public function showLogin():void {
        require __DIR__ . '/../../views/auth/login.php';
    }

    public function login():void {
        $email = trim ($_POST['email'] ?? '');
        $password = trim ($_POST['password']?? '');

        $userModel = new User();
        $user = $userModel-> verify ($email, $password);
        if (!$user){
            $error = 'Credenciales inválidas';
            require __DIR__ . '/../../views/auth/login.php';
            return;
        }
        $_SESSION['user']=[
            'id'=>$user['id'],
            'name'=>$user['name'],
            'email'=>$user['email'],
            'role'=>$user['role'],
        ];
        if ($user['role'] === 'admin') {
            header('Location: ' . BASE_URL . '/admin');
        }else {
            header('Location: ' . BASE_URL . '/');
        }
        exit;
    }  
    
    public function showRegister():void {
        require __DIR__ . '/../../views/auth/register.php';
    }

    public function register():void {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        
        $userModel = new User();
        if ($name === '' || $email === '' || strlen($password) < 6) {
            $error = 'Completa todos los campos. La contraseña debe tener al menos 6 caracteres.';
            require __DIR__ . '/../../views/auth/register.php';
            return;
        }

        if ($userModel->findByEmail($email)) {
            $error = 'Ese correo ya está registrado.';
            require __DIR__ . '/../../views/auth/register.php';
            return;
        }
        
        $userId = $userModel->create($name, $email, $password);
        $_SESSION['user'] = [
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'role' => 'cliente',
        ];
        header('Location: ' . BASE_URL . '/');
        exit;
    }

    public function logout():void {
        session_destroy();
        header('Location: ' . BASE_URL . '/');
        exit;
    }
    
    


}