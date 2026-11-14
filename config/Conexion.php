<?php

class Conexion {

    private string $host = 'localhost';
    private string $bd = 'gimnasio_db';
    private string $usuario = 'root';
    private string $clave = 'jherson';

    public function conectar(): PDO {

        try {

            $conexion = new PDO(
                "mysql:host={$this->host};dbname={$this->bd};charset=utf8mb4",
                $this->usuario,
                $this->clave,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );

            return $conexion;

        } catch (PDOException $e) {

            die('Error de conexión a MySQL: ' . $e->getMessage());

        }
    }
}