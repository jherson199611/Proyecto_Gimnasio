create database gimnasio
use gimnasio;

create table socios (
    id_socio int auto_increment primary key,
    dni char(8) not null unique,
    nombres varchar(80) not null,
    apellidos varchar(80) not null,
    telefono varchar(15),
    email varchar(100),
    fecha_nacimiento date,
    fecha_registro date not null default (current_date)
);

create table seguimiento_progreso (
    id_seguimiento int auto_increment primary key,
    id_socio int not null,
    fecha date not null default (current_date),
    peso_kg decimal(5,2) not null,
    pecho_cm decimal(5,2),
    cintura_cm decimal(5,2),
    cadera_cm decimal(5,2),
    brazo_cm decimal(5,2),
    pierna_cm decimal(5,2),
    constraint fk_progreso_socio
        foreign key (id_socio) references socios(id_socio)
);
