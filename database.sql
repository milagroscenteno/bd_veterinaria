CREATE DATABASE IF NOT EXISTS Veterianaria
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE Veterianaria;

CREATE TABLE mascotas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre_mascota VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE NOT NULL,
    genero ENUM('Macho', 'Hembra') NOT NULL,
    raza VARCHAR(100) NOT NULL
);

INSERT INTO mascotas
(nombre_mascota, fecha_nacimiento, genero, raza)
VALUES
('Max', '2021-03-15', 'Macho', 'Labrador'),
('Luna', '2022-06-20', 'Hembra', 'Poodle'),
('Rocky', '2020-11-10', 'Macho', 'Bulldog'),
('Mia', '2023-01-05', 'Hembra', 'Chihuahua'),
('Toby', '2021-08-12', 'Macho', 'Golden Retriever'),
('Nala', '2022-04-18', 'Hembra', 'Pastor Alemán'),
('Firulais', '2019-09-25', 'Macho', 'Mestizo'),
('Kira', '2023-02-14', 'Hembra', 'Beagle'),
('Bruno', '2020-07-30', 'Macho', 'Boxer'),
('Coco', '2022-12-01', 'Hembra', 'Shih Tzu'),
('Simba', '2021-05-09', 'Macho', 'Persa'),
('Molly', '2020-10-22', 'Hembra', 'Siamés'),
('Tom', '2019-04-16', 'Macho', 'Angora'),
('Pelusa', '2023-03-11', 'Hembra', 'Mestizo'),
('Duke', '2022-09-07', 'Macho', 'Rottweiler'),
('Canela', '2021-12-19', 'Hembra', 'Cocker Spaniel'),
('Zeus', '2020-02-28', 'Macho', 'Doberman'),
('Princesa', '2022-07-13', 'Hembra', 'Pomerania'),
('Bobby', '2023-05-21', 'Macho', 'Dálmata'),
('Misha', '2021-11-03', 'Hembra', 'Persa');