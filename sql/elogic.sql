CREATE DATABASE elogic
    DEFAULT CHARACTER SET utf8;
USE elogic;

CREATE TABLE usuarios(
        id INT NOT NULL AUTO_INCREMENT UNIQUE,
        nombre_usuario VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(100) NOT NULL,
        nombres VARCHAR(100) NOT NULL,
        apellidos VARCHAR(100) NOT NULL,
        cargo TINYINT NOT NULL,
        PRIMARY KEY(id)
);

CREATE TABLE rfq(
        id INT NOT NULL AUTO_INCREMENT UNIQUE,
        id_usuario INT NOT NULL,
        usuario_designado INT NOT NULL,
        canal VARCHAR(100) NOT NULL,
        email_code VARCHAR(100) NOT NULL,
        type_of_bid VARCHAR(100) NOT NULL,
        issue_date DATE NOT NULL,
        end_date DATETIME NOT NULL,
        status TINYINT NOT NULL,
        completado TINYINT NOT NULL,
        amount VARCHAR(50),
        proposal INT,
        comments VARCHAR(100),
        award TINYINT NOT NULL,
        fecha_completado DATE,
        PRIMARY KEY(id),
        FOREIGN KEY(id_usuario)
            REFERENCES usuarios(id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT
);