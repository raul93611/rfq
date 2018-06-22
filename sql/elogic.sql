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
        email VARCHAR(100) NOT NULL UNIQUE,
        PRIMARY KEY(id)
);

CREATE TABLE rfq(
        id INT NOT NULL AUTO_INCREMENT UNIQUE,
        id_usuario INT NOT NULL,
        usuario_designado INT NOT NULL,
        canal VARCHAR(100) NOT NULL,
        email_code VARCHAR(100) NOT NULL,
        type_of_bid VARCHAR(100) NOT NULL,
        issue_date VARCHAR(100) NOT NULL,
        end_date VARCHAR(100) NOT NULL,
        status TINYINT NOT NULL,
        completado TINYINT NOT NULL,
        total_cost DECIMAL(10,2),
        total_price DECIMAL(10,2),
        comments VARCHAR(100),
        award TINYINT NOT NULL,
        fecha_completado DATE,
        fecha_submitted DATE,
        fecha_award DATE,
        payment_terms VARCHAR(100) NOT NULL,
        address TEXT CHARACTER SET utf8 NOT NULL,
        ship_to TEXT CHARACTER SET utf8 NOT NULL,
        expiration_date DATE NOT NULL,
        ship_via VARCHAR(100) NOT NULL,
        taxes DECIMAL(10,2) NOT NULL,
        profit DECIMAL(10,2) NOT NULL,
        PRIMARY KEY(id),
        FOREIGN KEY(id_usuario)
            REFERENCES usuarios(id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT
);

CREATE TABLE item(
        id INT NOT NULL AUTO_INCREMENT UNIQUE,
        id_rfq INT NOT NULL,
        id_usuario INT NOT NULL,
        provider_menor INT NOT NULL,
        brand VARCHAR(100) NOT NULL,
        brand_project VARCHAR(100) NOT NULL,
        part_number VARCHAR(100) NOT NULL,
        part_number_project VARCHAR(100) NOT NULL,
        description TEXT CHARACTER SET utf8 NOT NULL,
        description_project TEXT CHARACTER SET utf8 NOT NULL,
        quantity INT NOT NULL,
        unit_price DECIMAL(10,2) NOT NULL,
        total_price DECIMAL(10,2) NOT NULL,
        comments TEXT CHARACTER SET utf8 NOT NULL,
        website VARCHAR(255) NOT NULL,
        PRIMARY KEY(id),
        FOREIGN KEY(id_rfq)
            REFERENCES rfq(id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT,
        FOREIGN KEY(id_usuario)
            REFERENCES usuarios(id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT
);

CREATE TABLE provider(
        id INT NOT NULL AUTO_INCREMENT UNIQUE,
        id_item INT NOT NULL,
        provider VARCHAR(100) NOT NULL,
        price  DECIMAL(10,2) NOT NULL,
        PRIMARY KEY(id),
        FOREIGN KEY(id_item)
            REFERENCES item(id)
            ON UPDATE CASCADE
            ON DELETE RESTRICT
);

ALTER TABLE rfq AUTO_INCREMENT = 300;