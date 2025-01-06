<?php
require_once '../config/db.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    try {
        // Verificar si el correo ya está registrado
        $stmt = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'El correo electrónico ya está registrado.']);
        } else {
            // Validar contraseña (mínimo 8 caracteres, una mayúscula, un número y un carácter especial)
            if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
                echo json_encode(['status' => 'error', 'message' => 'La contraseña debe tener al menos 8 caracteres, una letra mayúscula, un número y un carácter especial.']);
            } else {
                // Encriptar contraseña
                $passwordHashed = password_hash($password, PASSWORD_BCRYPT);
                $tipo = 1; // Tipo de usuario por defecto: Usuario normal

                // Insertar nuevo usuario en la base de datos
                $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, tipo) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$nombre, $email, $passwordHashed, $tipo])) {
                    echo json_encode(['status' => 'success', 'message' => 'Usuario registrado correctamente. Redirigiendo a inicio de sesión...']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error al registrar el usuario. Inténtalo de nuevo.']);
                }
            }
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error de base de datos: ' . $e->getMessage()]);
    }
    exit();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="http://localhost/CRUD_Alojamientos/assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="auth-page">
    <div class="container mt-5 fade-in">
        <form id="registerForm" method="POST" class="register">
            <h2 class="text-center">Registro de Usuario</h2>

            <div class="mb-3">
                <input type="text" class="form-control" id="nombre" name="nombre" required placeholder="Nombre de usuario">
            </div>

            <div class="mb-3">
                <input type="email" class="form-control" id="email" name="email" required placeholder="Correo electronico">
            </div>

            <div class="mb-3">
                <input type="password" class="form-control" id="password" name="password" required placeholder="Contraseña">
            </div>

            <button type="submit" class="btn btn-primary w-100">Registrarse</button>

            <div class="mt-3 text-center">
                <p>¿Ya tienes una cuenta? <a href="index.php">Inicia sesión aquí</a></p>
            </div>
        </form>
    </div>
</body>
</html>
