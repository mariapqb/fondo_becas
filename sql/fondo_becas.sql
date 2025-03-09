-- Tabla de Usuarios
CREATE TABLE IF NOT EXISTS usuarios (
tipo_documento ENUM('CC', 'TI', 'CE', 'PAS') NOT NULL,
numero_documento VARCHAR(20) PRIMARY KEY,
contraseña VARCHAR(64) NOT NULL,  -- SHA-256 genera 64 caracteres
rol ENUM('Administrador', 'Beneficiario') NOT NULL
);

-- Tabla de Administradores
CREATE TABLE IF NOT EXISTS administradores (
id_admin INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(50) NOT NULL,
apellido VARCHAR(50) NOT NULL,
tipo_documento ENUM('CC', 'TI', 'CE', 'PAS') NOT NULL,
numero_documento VARCHAR(20) UNIQUE NOT NULL,
telefono VARCHAR(15),
email VARCHAR(100) UNIQUE NOT NULL,
direccion VARCHAR(255),
FOREIGN KEY (numero_documento) REFERENCES usuarios(numero_documento) ON DELETE CASCADE
);

-- Tabla de Instituciones Educativas
CREATE TABLE IF NOT EXISTS instituciones_educativas (
id_institucion INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(255) UNIQUE NOT NULL,
email VARCHAR(100),
direccion VARCHAR(255)
);

-- Tabla de Becas
CREATE TABLE IF NOT EXISTS becas (
id_beca INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(100) NOT NULL,
descripcion TEXT,
monto DECIMAL(10,2)
);

-- Tabla de Beneficiarios
CREATE TABLE IF NOT EXISTS beneficiarios (
id_beneficiario INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(50) NOT NULL,
apellido VARCHAR(50) NOT NULL,
tipo_documento ENUM('CC', 'TI', 'CE', 'PAS') NOT NULL,
numero_documento VARCHAR(20) UNIQUE NOT NULL,
telefono VARCHAR(15),
email VARCHAR(100) UNIQUE NOT NULL,
direccion VARCHAR(255),
fecha_nacimiento DATE,
id_institucion INT,
semestre_actual INT,
resolucion_beca VARCHAR(255),
FOREIGN KEY (id_institucion) REFERENCES instituciones_educativas(id_institucion),
FOREIGN KEY (numero_documento) REFERENCES usuarios(numero_documento) ON DELETE CASCADE
);

-- Tabla de Historial de Promedio Académico
CREATE TABLE IF NOT EXISTS historial_promedio_academico (
id_historial INT AUTO_INCREMENT PRIMARY KEY,
id_beneficiario INT,
promedio DECIMAL(4,2),
documento_notas LONGBLOB,
fecha_registro DATE,
FOREIGN KEY (id_beneficiario) REFERENCES beneficiarios(id_beneficiario) ON DELETE CASCADE
);

-- Tabla de Universidades
CREATE TABLE IF NOT EXISTS universidades (
id_universidad INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(255) UNIQUE NOT NULL,
direccion VARCHAR(255),
telefono VARCHAR(20),
correo VARCHAR(100)
);

-- Tabla de Programas Académicos
CREATE TABLE IF NOT EXISTS programas_academicos (
id_programa INT AUTO_INCREMENT PRIMARY KEY,
nombre VARCHAR(255) NOT NULL,
id_universidad INT,
cantidad_semestres INT,
malla_curricular LONGBLOB,
FOREIGN KEY (id_universidad) REFERENCES universidades(id_universidad)
);

-- Tabla de Criterios de Selección
CREATE TABLE IF NOT EXISTS criterios_seleccion (
id_criterio INT AUTO_INCREMENT PRIMARY KEY,
nombre ENUM(
'pruebas saber media academica',
'promedio academico',
'pruebas saber educación adultos',
'deportivo',
'artistico o cultural'
) NOT NULL
);

-- Tabla de Solicitudes
CREATE TABLE IF NOT EXISTS solicitudes (
id_solicitud INT AUTO_INCREMENT PRIMARY KEY,
id_beneficiario INT,
id_beca INT,
id_criterio INT,
id_programa INT,
estado ENUM('pendiente','aprobada','rechazada','renovada','cancelada') NOT NULL,
fecha_solicitud DATE,
FOREIGN KEY (id_beneficiario) REFERENCES beneficiarios(id_beneficiario) ON DELETE CASCADE,
FOREIGN KEY (id_beca) REFERENCES becas(id_beca),
FOREIGN KEY (id_criterio) REFERENCES criterios_seleccion(id_criterio),
FOREIGN KEY (id_programa) REFERENCES programas_academicos(id_programa)
);

-- Tabla de Historial de Becas
CREATE TABLE IF NOT EXISTS historial_becas (
id_historial INT AUTO_INCREMENT PRIMARY KEY,
id_solicitud INT,
estado_anterior ENUM('pendiente','aprobada','rechazada','renovada','cancelada'),
estado_nuevo ENUM('pendiente','aprobada','rechazada','renovada','cancelada'),
fecha_cambio DATE,
observaciones TEXT,
FOREIGN KEY (id_solicitud) REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE
);

-- Tabla de Pagos
CREATE TABLE IF NOT EXISTS pagos (
id_pago INT AUTO_INCREMENT PRIMARY KEY,
id_solicitud INT,
monto DECIMAL(10,2),
fecha_pago DATE,
FOREIGN KEY (id_solicitud) REFERENCES solicitudes(id_solicitud) ON DELETE CASCADE
);

-- Tabla de PQRS (Peticiones, Quejas, Reclamos y Sugerencias)
CREATE TABLE IF NOT EXISTS pqrs (
id_pqrs INT AUTO_INCREMENT PRIMARY KEY,
fecha DATE,
nombre_completo VARCHAR(100) NOT NULL,
documento_identidad VARCHAR(20) NOT NULL,
correo_electronico VARCHAR(100),
direccion VARCHAR(255),
asunto VARCHAR(150) NOT NULL,
descripcion TEXT NOT NULL,
solicitud_especifica TEXT
);

INSERT INTO usuarios (tipo_documento, numero_documento, contraseña, rol)
VALUES ('CC', '41941285', SHA2('Pauli1285', 256), 'Administrador');

INSERT INTO administradores (nombre, apellido, tipo_documento, numero_documento, telefono, email, direccion)
VALUES ('Paula Andrea', 'Blandón Salazar', 'CC', '41941285', '3105018058', 'pblandonsem@tic.edu.co', 'carrera 22 #10a-61 ed Granada Center');

