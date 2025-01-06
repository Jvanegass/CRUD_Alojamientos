<?php
session_start();
require_once '../config/db.php';
require_once '../models/Alojamiento.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$alojamientoModel = new Alojamiento($conn);
$alojamientos = $alojamientoModel->getAllAlojamientos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alojamientos</title>
    <link rel="stylesheet" href="http://localhost/CRUD_Alojamientos/assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="d-flex justify-content-between align-items-center p-3 bg-light shadow">
        <h3 class="text-primary">Alojamientos</h3>
        <a href="user_account.php" class="btn btn-outline-primary">Volver a mi cuenta</a>
    </header>

    <div class="container mt-5 fade-in">
        <h1 class="text-center">Alojamientos Disponibles</h1>
        <div class="row">
            <?php foreach ($alojamientos as $alojamiento): ?>
                <div class="col-md-4 mb-3">
                    <div class="card fade-in">
                        <img src="<?= htmlspecialchars($alojamiento['imagen_url']) ?>" class="card-img-top" alt="<?= htmlspecialchars($alojamiento['nombre']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($alojamiento['nombre']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($alojamiento['descripcion']) ?></p>
                            <p class="card-text"><small class="text-muted"><?= htmlspecialchars($alojamiento['ubicacion']) ?></small></p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reserveModal" data-id="<?= $alojamiento['id'] ?>" data-nombre="<?= htmlspecialchars($alojamiento['nombre']) ?>">Seleccionar</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal de reserva -->
    <div class="modal fade" id="reserveModal" tabindex="-1" aria-labelledby="reserveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reserveModalLabel">Reservar Alojamiento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="../controllers/alojamientosController.php">
                    <div class="modal-body">
                        <input type="hidden" id="alojamiento_id" name="alojamiento_id">
                        <p><strong>Alojamiento:</strong> <span id="alojamiento_nombre"></span></p>
                        <div class="mb-3">
                            <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_fin" class="form-label">Fecha de fin</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="reservar" class="btn btn-primary">Reservar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const reserveModal = document.getElementById('reserveModal');
        reserveModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget;
            const alojamientoId = button.getAttribute('data-id');
            const alojamientoNombre = button.getAttribute('data-nombre');

            document.getElementById('alojamiento_id').value = alojamientoId;
            document.getElementById('alojamiento_nombre').textContent = alojamientoNombre;
        });
    </script>
    <script src="../assets/js/scripts.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
