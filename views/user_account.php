<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Obtener la información del usuario
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT nombre, email FROM usuarios WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Obtener los alojamientos reservados por el usuario
$stmt = $conn->prepare("SELECT a.*, ua.fecha_inicio, ua.fecha_fin 
                        FROM usuario_alojamientos ua 
                        JOIN alojamientos a ON ua.alojamiento_id = a.id 
                        WHERE ua.usuario_id = ?");
$stmt->execute([$user_id]);
$alojamientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta de Usuario</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="d-flex justify-content-between align-items-center p-3 bg-light shadow">
        <h3 class="text-primary">Cuenta de Usuario</h3>
        <div>
            <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#profileModal">
                <i class="bi bi-person-circle"></i> Perfil
            </button>
            <a href="../controllers/logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>
    </header>

    <!-- Contenedor principal -->
    <div class="container mt-5 p-4 bg-white rounded shadow fade-in" style="background: rgba(255, 255, 255, 0.8);">
        <!-- Botón para elegir alojamiento -->
        <div class="d-flex justify-content-end mb-4">
            <a href="alojamientos.php" class="btn btn-primary">+ Elegir Alojamiento</a>
        </div>

        <!-- Mostrar alojamientos del usuario -->
        <h2 class="text-center">Mis Alojamientos Reservados</h2>
        <?php if (count($alojamientos) === 0): ?>
            <p class="text-center text-muted">No tienes alojamientos seleccionados.</p>
        <?php else: ?>
            <div class="row">
                <?php foreach ($alojamientos as $alojamiento): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card fade-in">
                            <img src="<?= htmlspecialchars($alojamiento['imagen_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($alojamiento['nombre']) ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($alojamiento['nombre']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($alojamiento['descripcion']) ?></p>
                                <p class="card-text"><small class="text-muted"><?= htmlspecialchars($alojamiento['ubicacion']) ?></small></p>
                                <p><strong>Desde:</strong> <?= htmlspecialchars($alojamiento['fecha_inicio']) ?></p>
                                <p><strong>Hasta:</strong> <?= htmlspecialchars($alojamiento['fecha_fin']) ?></p>
                                <form method="POST" action="../controllers/alojamientosController.php">
                                    <input type="hidden" name="alojamiento_id" value="<?= $alojamiento['id'] ?>">
                                    <button type="submit" name="cancelar_reserva" class="btn btn-danger w-100">Cancelar Reserva</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="text-center py-3 bg-light mt-4">
        &copy; 2025 CRUD Alojamientos. Todos los derechos reservados.
    </footer>

    <!-- Modal de perfil -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">Perfil del Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Nombre:</strong> <?= htmlspecialchars($user['nombre']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
