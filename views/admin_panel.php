<?php
session_start();
require_once '../config/db.php';

// Verificar si el usuario es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] != 2) {
    header('Location: login.php');
    exit();
}

// Obtener los alojamientos de la base de datos
$stmt = $conn->prepare("SELECT * FROM alojamientos");
$stmt->execute();
$alojamientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header class="d-flex justify-content-between align-items-center p-3 bg-light shadow">
        <h3 class="text-primary">Panel de Administración</h3>
        <div>
            <button class="btn btn-outline-primary me-2" data-bs-toggle="modal" data-bs-target="#profileModal">
                <i class="bi bi-person-circle"></i> Perfil
            </button>
            <a href="../controllers/logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>
    </header>

    <!-- Contenedor principal -->
    <div class="container mt-5 p-4 bg-white rounded shadow fade-in" style="background: rgba(255, 255, 255, 0.8);">
        <!-- Mensaje de retroalimentación -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info fade-in"><?= htmlspecialchars($_SESSION['message']) ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <!-- Botón para abrir el modal de agregar alojamiento -->
        <div class="d-flex justify-content-between mb-4">
            <h4 class="text-primary">Listado de Alojamientos</h4>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addAlojamientoModal">+ Crear Alojamiento</button>
        </div>

        <!-- Tabla de alojamientos -->
        <table class="table table-hover table-striped">
            <thead class="table-primary">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Ubicación</th>
                    <th>Imagen</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alojamientos as $alojamiento): ?>
                    <tr>
                        <td><?= htmlspecialchars($alojamiento['id']) ?></td>
                        <td><?= htmlspecialchars($alojamiento['nombre']) ?></td>
                        <td><?= htmlspecialchars($alojamiento['descripcion']) ?></td>
                        <td><?= htmlspecialchars($alojamiento['ubicacion']) ?></td>
                        <td><img src="<?= htmlspecialchars($alojamiento['imagen_url']) ?>" alt="Imagen" width="100"></td>
                        <td><?= $alojamiento['status'] == 1 ? 'Disponible' : 'No Disponible' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
                    <h5 class="modal-title" id="profileModalLabel">Perfil del Administrador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Nombre:</strong> <?= htmlspecialchars($_SESSION['user_nombre']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['user_email'] ?? 'No disponible') ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de agregar alojamiento -->
    <div class="modal fade" id="addAlojamientoModal" tabindex="-1" aria-labelledby="addAlojamientoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAlojamientoModalLabel">Agregar Alojamiento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="../controllers/alojamientosController.php">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Alojamiento</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="ubicacion" class="form-label">Ubicación</label>
                            <input type="text" class="form-control" id="ubicacion" name="ubicacion" required>
                        </div>
                        <div class="mb-3">
                            <label for="imagen_url" class="form-label">URL de la Imagen</label>
                            <input type="url" class="form-control" id="imagen_url" name="imagen_url" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Estatus</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="1">Disponible</option>
                                <option value="0">No Disponible</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="add_alojamiento" class="btn btn-primary">Guardar Alojamiento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>