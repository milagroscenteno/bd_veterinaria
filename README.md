
# Backend Veterianaria

API REST desarrollada con PHP y MariaDB para administrar mascotas.

## Tecnologías
- PHP
- MariaDB
- XAMPP
- Postman

## Base de datos
Nombre: Veterianaria
Tabla: mascotas

## Campos
- id
- nombre_mascota
- fecha_nacimiento
- genero
- raza

## Instalación
1. Copiar la carpeta a C:\xampp\htdocs.
2. Importar database.sql en phpMyAdmin.
3. Configurar las credenciales en conexion.php.
4. Iniciar Apache y MySQL en XAMPP.

## URL base
http://localhost/backend-veterianaria/index.php

## Endpoints

| Método | URL | Operación |
|---|---|---|
| GET | /index.php | Listar mascotas |
| GET | /index.php?id=1 | Consultar por ID |
| POST | /index.php | Registrar |
| PUT | /index.php?id=1 | Actualizar |
| DELETE | /index.php?id=1 | Eliminar |

## Formato
Las solicitudes POST y PUT reciben JSON.
