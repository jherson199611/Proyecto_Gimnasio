-- =========================================================
-- BASE DE DATOS: SISTEMA DE GESTION DE GIMNASIO
-- Motor: MySQL / MariaDB
-- =========================================================

CREATE DATABASE IF NOT EXISTS gimnasio_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE gimnasio_db;

-- ---------------------------------------------------------
-- 1. ROLES Y USUARIOS
-- ---------------------------------------------------------

CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(40) NOT NULL UNIQUE,
    descripcion VARCHAR(150)
);

CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    correo VARCHAR(100),
    password VARCHAR(255) NOT NULL,
    rol_id INT NOT NULL,
    estado TINYINT DEFAULT 1,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_usuarios_roles
        FOREIGN KEY (rol_id) REFERENCES roles(id)
);

-- ---------------------------------------------------------
-- 2. SOCIOS, PLANES Y MEMBRESIAS
-- ---------------------------------------------------------

CREATE TABLE IF NOT EXISTS socios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    dni CHAR(8) NOT NULL UNIQUE,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    correo VARCHAR(100),
    direccion VARCHAR(180),
    fecha_registro DATE NOT NULL
);

CREATE TABLE IF NOT EXISTS planes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(40) NOT NULL UNIQUE,
    precio DECIMAL(10, 2) NOT NULL,
    duracion_dias INT NOT NULL,
    descripcion VARCHAR(180)
);

CREATE TABLE IF NOT EXISTS membresias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    socio_id INT NOT NULL,
    plan_id INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    estado ENUM('Vigente', 'Vencida', 'Cancelada') DEFAULT 'Vigente',
    CONSTRAINT fk_membresias_socios
        FOREIGN KEY (socio_id) REFERENCES socios(id),
    CONSTRAINT fk_membresias_planes
        FOREIGN KEY (plan_id) REFERENCES planes(id)
);

-- ---------------------------------------------------------
-- 3. SEGUIMIENTO DEL PROGRESO FISICO
-- ---------------------------------------------------------

CREATE TABLE IF NOT EXISTS seguimiento_progreso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    socio_id INT NOT NULL,
    fecha DATE NOT NULL,
    peso DECIMAL(5, 2),
    altura DECIMAL(4, 2),
    cintura DECIMAL(5, 2),
    pecho DECIMAL(5, 2),
    observaciones TEXT,
    CONSTRAINT fk_progreso_socios
        FOREIGN KEY (socio_id) REFERENCES socios(id)
);

-- ---------------------------------------------------------
-- 4. EJERCICIOS Y RUTINAS
-- ---------------------------------------------------------

CREATE TABLE IF NOT EXISTS rutinas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    nivel ENUM('Básico', 'Intermedio', 'Avanzado') NOT NULL,
    descripcion TEXT
);

CREATE TABLE IF NOT EXISTS ejercicios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    musculo VARCHAR(80) NOT NULL,
    descripcion TEXT
);

CREATE TABLE IF NOT EXISTS detalle_rutina (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rutina_id INT NOT NULL,
    ejercicio_id INT NOT NULL,
    series INT NOT NULL,
    repeticiones INT NOT NULL,
    descanso_seg INT DEFAULT 60,
    CONSTRAINT fk_detalle_rutinas
        FOREIGN KEY (rutina_id) REFERENCES rutinas(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_detalle_ejercicios
        FOREIGN KEY (ejercicio_id) REFERENCES ejercicios(id)
);

-- ---------------------------------------------------------
-- 5. PAGOS, ASISTENCIAS Y ASIGNACIONES
-- ---------------------------------------------------------

CREATE TABLE IF NOT EXISTS pagos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    membresia_id INT NOT NULL,
    fecha DATE NOT NULL,
    monto DECIMAL(10, 2) NOT NULL,
    metodo ENUM('Efectivo', 'Yape', 'Tarjeta') NOT NULL,
    referencia VARCHAR(100),
    CONSTRAINT fk_pagos_membresias
        FOREIGN KEY (membresia_id) REFERENCES membresias(id)
);

CREATE TABLE IF NOT EXISTS asistencias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    socio_id INT NOT NULL,
    fecha_hora DATETIME NOT NULL,
    observacion VARCHAR(150),
    CONSTRAINT fk_asistencias_socios
        FOREIGN KEY (socio_id) REFERENCES socios(id)
);

CREATE TABLE IF NOT EXISTS asignaciones_rutina (
    id INT AUTO_INCREMENT PRIMARY KEY,
    socio_id INT NOT NULL,
    rutina_id INT NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NULL,
    estado ENUM('Activa', 'Finalizada') DEFAULT 'Activa',
    CONSTRAINT fk_asignaciones_socios
        FOREIGN KEY (socio_id) REFERENCES socios(id),
    CONSTRAINT fk_asignaciones_rutinas
        FOREIGN KEY (rutina_id) REFERENCES rutinas(id)
);

-- ---------------------------------------------------------
-- 6. DATOS INICIALES
-- ---------------------------------------------------------

INSERT IGNORE INTO roles (nombre, descripcion) VALUES
    ('Administrador', 'Acceso completo al sistema'),
    ('Recepcionista', 'Gestion de socios y asistencias'),
    ('Entrenador', 'Gestion de rutinas y seguimiento');

INSERT IGNORE INTO planes (nombre, precio, duracion_dias, descripcion) VALUES
    ('Básico', 50.00, 30, 'Acceso básico por 30 días'),
    ('Estándar', 80.00, 30, 'Acceso estándar por 30 días'),
    ('Premium', 120.00, 30, 'Acceso premium por 30 días');

-- Usuario inicial: admin
-- Contraseña inicial: password
-- Se recomienda cambiarla después del primer ingreso.
INSERT INTO usuarios (nombre, usuario, correo, password, rol_id)
SELECT
    'Administrador',
    'admin',
    'admin@gimnasio.local',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi.',
    (SELECT id FROM roles WHERE nombre = 'Administrador' LIMIT 1)
WHERE NOT EXISTS (
    SELECT 1 FROM usuarios WHERE usuario = 'admin'
);
