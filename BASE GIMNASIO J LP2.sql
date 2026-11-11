create database if not exists gimnasio_db character set utf8mb4 collate utf8mb4_unicode_ci;
use gimnasio_db;

-- tabla 1: planes
create table if not exists planes (
    id int auto_increment primary key,
    nombre varchar(50) not null,
    descripcion text,
    precio decimal(10,2) not null,
    duracion_dias int not null,
    estado enum('activo', 'inactivo') default 'activo',
    created_at timestamp default current_timestamp
);

-- tabla 2: membresias
create table if not exists membresias (
    id int auto_increment primary key,
    socio_id int not null, -- id del socio que adquiere el plan
    plan_id int not null,
    fecha_inicio date not null,
    fecha_vencimiento date not null,
    estado enum('vigente', 'vencida', 'cancelada') default 'vigente',
    created_at timestamp default current_timestamp,
    foreign key (plan_id) references planes(id) on delete cascade
);

-- insertar los 3 planes solicitados
insert into planes (nombre, descripcion, precio, duracion_dias) values
('básico', 'acceso a zona de pesas', 50.00, 30),
('estándar', 'acceso a pesas + clases grupales', 80.00, 30),
('premium', 'acceso total + entrenador personalizado', 120.00, 30);