create database if not exists gimnasio_db
    character set utf8mb4
    collate utf8mb4_unicode_ci;

use gimnasio_db;

create table if not exists roles (
    id int auto_increment primary key,
    nombre varchar(40) not null unique,
    descripcion varchar(150)
);

create table if not exists usuarios (
    id int auto_increment primary key,
    nombre varchar(100) not null,
    usuario varchar(50) not null unique,
    correo varchar(100),
    password varchar(255) not null,
    rol_id int not null,
    estado tinyint default 1,
    creado_en timestamp default current_timestamp,
    constraint fk_usuarios_roles
        foreign key (rol_id) references roles(id)
);

create table if not exists socios (
    id int auto_increment primary key,
    dni char(8) not null unique,
    nombres varchar(100) not null,
    apellidos varchar(100) not null,
    telefono varchar(20),
    correo varchar(100),
    direccion varchar(180),
    fecha_registro date not null
);

create table if not exists planes (
    id int auto_increment primary key,
    nombre varchar(40) not null unique,
    precio decimal(10, 2) not null,
    duracion_dias int not null,
    descripcion varchar(180)
);

create table if not exists membresias (
    id int auto_increment primary key,
    socio_id int not null,
    plan_id int not null,
    fecha_inicio date not null,
    fecha_fin date not null,
    estado enum('vigente', 'vencida', 'cancelada') default 'vigente',
    constraint fk_membresias_socios
        foreign key (socio_id) references socios(id),
    constraint fk_membresias_planes
        foreign key (plan_id) references planes(id)
);

create table if not exists seguimiento_progreso (
    id int auto_increment primary key,
    socio_id int not null,
    fecha date not null,
    peso decimal(5, 2),
    altura decimal(4, 2),
    cintura decimal(5, 2),
    pecho decimal(5, 2),
    observaciones text,
    constraint fk_progreso_socios
        foreign key (socio_id) references socios(id)
);

create table if not exists rutinas (
    id int auto_increment primary key,
    nombre varchar(100) not null,
    nivel enum('básico', 'intermedio', 'avanzado') not null,
    descripcion text
);

create table if not exists ejercicios (
    id int auto_increment primary key,
    nombre varchar(100) not null,
    musculo varchar(80) not null,
    descripcion text
);

create table if not exists detalle_rutina (
    id int auto_increment primary key,
    rutina_id int not null,
    ejercicio_id int not null,
    series int not null,
    repeticiones int not null,
    descanso_seg int default 60,
    constraint fk_detalle_rutinas
        foreign key (rutina_id) references rutinas(id)
        on delete cascade,
    constraint fk_detalle_ejercicios
        foreign key (ejercicio_id) references ejercicios(id)
);

create table if not exists pagos (
    id int auto_increment primary key,
    membresia_id int not null,
    fecha date not null,
    monto decimal(10, 2) not null,
    metodo enum('efectivo', 'yape', 'tarjeta') not null,
    referencia varchar(100),
    constraint fk_pagos_membresias
        foreign key (membresia_id) references membresias(id)
);

create table if not exists asistencias (
    id int auto_increment primary key,
    socio_id int not null,
    fecha_hora datetime not null,
    observacion varchar(150),
    constraint fk_asistencias_socios
        foreign key (socio_id) references socios(id)
);

create table if not exists asignaciones_rutina (
    id int auto_increment primary key,
    socio_id int not null,
    rutina_id int not null,
    fecha_inicio date not null,
    fecha_fin date null,
    estado enum('activa', 'finalizada') default 'activa',
    constraint fk_asignaciones_socios
        foreign key (socio_id) references socios(id),
    constraint fk_asignaciones_rutinas
        foreign key (rutina_id) references rutinas(id)
);

insert ignore into roles (nombre, descripcion) values
    ('administrador', 'acceso completo al sistema'),
    ('recepcionista', 'gestion de socios y asistencias'),
    ('entrenador', 'gestion de rutinas y seguimiento');

insert ignore into planes (nombre, precio, duracion_dias, descripcion) values
    ('básico', 50.00, 30, 'acceso básico por 30 días'),
    ('estándar', 80.00, 30, 'acceso estándar por 30 días'),
    ('premium', 120.00, 30, 'acceso premium por 30 días');

insert into usuarios (nombre, usuario, correo, password, rol_id)
select
    'administrador',
    'admin',
    'admin@gimnasio.local',
    '$2y$10$92ixunkpjoo0roq5bymi.ye4okoea3ro9llc/.og/at2uhewg/igi.',
    (select id from roles where nombre = 'administrador' limit 1)
where not exists (
    select 1 from usuarios where usuario = 'admin'
);