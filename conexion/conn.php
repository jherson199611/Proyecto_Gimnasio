<?php

class Conexion
{
    private static ?Conexion $instancia = null;
    private ?PDO $pdo = null;

    private string $host = "localhost";
    private string $bd   = "techsolutions_db";
    private string $usuario = "root";
    private string $clave   = "julio";
    private function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->bd};charset=utf8";

        try {
            $this->pdo = new PDO($dsn, $this->usuario, $this->clave, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
    public static function getInstancia(): Conexion
    {
        if (self::$instancia === null) {
            self::$instancia = new self();
        }
        return self::$instancia;
    }
    public function getPDO(): PDO
    {
        return $this->pdo;
    }
    public function cerrarConexion(): void
    {
        $this->pdo = null;
        self::$instancia = null;
    }
}
