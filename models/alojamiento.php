<?php
class Alojamiento {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Método para agregar un alojamiento
    public function addAlojamiento($nombre, $descripcion, $ubicacion, $imagen_url, $status) {
        $sql = "INSERT INTO alojamientos (nombre, descripcion, ubicacion, imagen_url, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$nombre, $descripcion, $ubicacion, $imagen_url, $status]);
    }

    // Método para obtener todos los alojamientos disponibles
    public function getAllAlojamientos() {
        $sql = "SELECT * FROM alojamientos WHERE status = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para agregar una reserva de alojamiento
    public function addReserva($usuario_id, $alojamiento_id, $fecha_inicio, $fecha_fin) {
        $sql = "INSERT INTO usuario_alojamientos (usuario_id, alojamiento_id, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$usuario_id, $alojamiento_id, $fecha_inicio, $fecha_fin]);
    }

    // Método para cancelar una reserva de alojamiento
    public function cancelarReserva($usuario_id, $alojamiento_id) {
        $sql = "DELETE FROM usuario_alojamientos WHERE usuario_id = ? AND alojamiento_id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$usuario_id, $alojamiento_id]);
    }

    // Método para obtener los alojamientos reservados por un usuario
    public function getAlojamientosReservados($usuario_id) {
        $sql = "SELECT a.*, ua.fecha_inicio, ua.fecha_fin 
                FROM usuario_alojamientos ua 
                JOIN alojamientos a ON ua.alojamiento_id = a.id 
                WHERE ua.usuario_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
