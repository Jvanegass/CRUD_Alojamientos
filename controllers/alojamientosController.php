<?php
session_start();
require_once '../config/db.php';
require_once '../models/Alojamiento.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/index.php');
    exit();
}

$alojamientoModel = new Alojamiento($conn);

// Agregar alojamiento (para administrador)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_alojamiento'])) {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $ubicacion = trim($_POST['ubicacion']);
    $imagen_url = trim($_POST['imagen_url']);
    $status = intval($_POST['status']);

    if ($alojamientoModel->addAlojamiento($nombre, $descripcion, $ubicacion, $imagen_url, $status)) {
        $_SESSION['message'] = 'Alojamiento agregado correctamente.';
    } else {
        $_SESSION['message'] = 'Error al agregar el alojamiento.';
    }

    header('Location: ../views/admin_panel.php');
    exit();
}

// Reservar alojamiento (para usuario)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reservar'])) {
    $alojamiento_id = intval($_POST['alojamiento_id']);
    $usuario_id = $_SESSION['user_id'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];

    // Insertar la reserva en la tabla usuario_alojamientos
    $stmt = $conn->prepare("INSERT INTO usuario_alojamientos (usuario_id, alojamiento_id, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$usuario_id, $alojamiento_id, $fecha_inicio, $fecha_fin])) {
        $_SESSION['message'] = 'Reserva realizada correctamente.';
    } else {
        $_SESSION['message'] = 'Error al realizar la reserva.';
    }

    header('Location: ../views/user_account.php');
    exit();
}

// Cancelar reserva (para usuario)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancelar_reserva'])) {
    $alojamiento_id = intval($_POST['alojamiento_id']);
    $usuario_id = $_SESSION['user_id'];

    // Eliminar la reserva del usuario
    $stmt = $conn->prepare("DELETE FROM usuario_alojamientos WHERE usuario_id = ? AND alojamiento_id = ?");
    if ($stmt->execute([$usuario_id, $alojamiento_id])) {
        $_SESSION['message'] = 'Reserva cancelada correctamente.';
    } else {
        $_SESSION['message'] = 'Error al cancelar la reserva.';
    }

    header('Location: ../views/user_account.php');
    exit();
}
?>
