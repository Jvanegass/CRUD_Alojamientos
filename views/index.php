<?php
session_start();
require_once '../config/db.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    try {
        // Consulta a la base de datos para verificar el email
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificación de la contraseña
        if ($user && password_verify($password, $user['password'])) {
            // Guardar datos del usuario en la sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_tipo'] = $user['tipo'];
            $_SESSION['user_nombre'] = $user['nombre'];
            $_SESSION['user_email'] = $user['email'];

            // Devolver respuesta JSON con redirección
            if ($user['tipo'] == 2) {
                echo json_encode(['status' => 'success', 'redirect' => 'admin_panel.php']);
            } else {
                echo json_encode(['status' => 'success', 'redirect' => 'user_account.php']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Credenciales incorrectas. Por favor, intenta de nuevo.']);
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
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="http://localhost/CRUD_Alojamientos/assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="auth-page">
    <div class="container mt-5">
        <!-- Mensaje de error si las credenciales son incorrectas -->
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger fade-in"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <!-- Formulario de inicio de sesión -->
        <form id="loginForm" method="POST" action="index.php" class="fade-in">
            <div class="mb-3">
                <h2 class="text-center">Iniciar Sesión</h2>
            </div>

            <div class="mb-3">
                <input type="email" class="form-control" id="inputEmail" name="email" placeholder="Correo electrónico" required>
            </div>

            <div class="mb-3">
                <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Contraseña" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>

            <div class="mt-3 text-center">
                <p>¿No tienes una cuenta? <a href="register.php" class="text-primary">Regístrate aquí</a></p>
            </div>
        </form>
    </div>

    <script src="../assets/js/scripts.js" defer></script>
</body>

</html>
