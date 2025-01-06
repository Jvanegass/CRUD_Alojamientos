<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_tipo'] = $user['tipo'];
        header('Location: user_account.php');
        exit();
    } else {
        $error_message = "Credenciales incorrectas. Por favor, intenta de nuevo.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Iniciar Sesión</h2>

        <!-- Mensaje de error si las credenciales son incorrectas -->
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger fade-in"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <form method="POST" class="fade-in">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        </form>

        <!-- Enlace para redirigir al registro de usuario -->
        <div class="mt-3">
            <p>¿No tienes una cuenta? <a href="register.php" class="text-primary">Regístrate aquí</a></p>
        </div>
    </div>

    <script src="../assets/js/scripts.js" defer></script>
</body>
</html>
