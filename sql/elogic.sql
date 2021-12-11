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
  status TINYINT NOT NULL,
  hash_recover_email VARCHAR(255) NOT NULL,
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
  additional VARCHAR(100) NOT NULL,
  shipping_cost DECIMAL(10,2) NOT NULL,
  shipping VARCHAR(100) NOT NULL,
  fullfillment TINYINT NOT NULL,
  fulfillment_date DATE,
  contract_number VARCHAR(255) NOT NULL,
  fulfillment_profit DECIMAL(10,2),
  services_fulfillment_profit DECIMAL(10,2),
  total_fulfillment DECIMAL(10,2),
  total_services_fulfillment DECIMAL(10,2),
  invoice TINYINT,
  invoice_date DATE,
  multi_year_project INT,
  submitted_invoice TINYINT,
  submitted_invoice_date DATE,
  fulfillment_pending TINYINT,
  fulfillment_shipping_cost DECIMAL(10,2),
  fulfillment_shipping VARCHAR(255),
  type_of_contract VARCHAR(255),
  PRIMARY KEY(id),
  FOREIGN KEY(id_usuario)
      REFERENCES usuarios(id)
      ON UPDATE CASCADE
      ON DELETE RESTRICT
);

CREATE TABLE comments(
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  id_rfq INT NOT NULL,
  id_usuario INT NOT NULL,
  comment TEXT CHARACTER SET utf8 NOT NULL,
  fecha_comment DATETIME,
  PRIMARY KEY(id),
  FOREIGN KEY(id_rfq)
    REFERENCES rfq(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);

CREATE TABLE services(
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  id_rfq INT NOT NULL,
  description TEXT CHARACTER SET utf8 NOT NULL,
  quantity INT NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  total_price DECIMAL(10,2) NOT NULL,
  fulfillment_profit DECIMAL(10,2),
  PRIMARY KEY(id),
  FOREIGN KEY(id_rfq)
      REFERENCES rfq(id)
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
  additional VARCHAR(100) NOT NULL,
  fulfillment_profit DECIMAL(10,2),
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

CREATE TABLE subitems(
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  id_item INT NOT NULL,
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
  additional VARCHAR(100) NOT NULL,
  fulfillment_profit DECIMAL(10,2),
  PRIMARY KEY(id),
  FOREIGN KEY(id_item)
      REFERENCES item(id)
      ON UPDATE CASCADE
      ON DELETE RESTRICT
);

CREATE TABLE provider_subitems(
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  id_subitem INT NOT NULL,
  provider VARCHAR(255) NOT NULL,
  price  DECIMAL(10,2) NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(id_subitem)
      REFERENCES subitems(id)
      ON UPDATE CASCADE
      ON DELETE RESTRICT
);

CREATE TABLE re_quotes(
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  id_rfq INT NOT NULL,
  total_cost DECIMAL(20,2) NOT NULL,
  total_price DECIMAL(20,2) NOT NULL,
  payment_terms VARCHAR(255) NOT NULL,
  taxes DECIMAL(20,2) NOT NULL,
  profit DECIMAL(20,2) NOT NULL,
  additional DECIMAL(20,2) NOT NULL,
  shipping_cost DECIMAL (20,2) NOT NULL,
  shipping VARCHAR(255) NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(id_rfq)
    REFERENCES rfq(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);

CREATE TABLE re_quote_items(
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  id_re_quote INT NOT NULL,
  brand TEXT CHARACTER SET utf8 NOT NULL,
  brand_project TEXT CHARACTER SET utf8 NOT NULL,
  part_number TEXT CHARACTER SET utf8 NOT NULL,
  part_number_project TEXT CHARACTER SET utf8 NOT NULL,
  description TEXT CHARACTER SET utf8 NOT NULL,
  description_project TEXT CHARACTER SET utf8 NOT NULL,
  quantity INT NOT NULL,
  unit_price DECIMAL(20,2) NOT NULL,
  total_price DECIMAL(20,2) NOT NULL,
  comments TEXT CHARACTER SET utf8 NOT NULL,
  website VARCHAR(255) NOT NULL,
  additional DECIMAL(20,2) NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(id_re_quote)
    REFERENCES re_quotes(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);

CREATE TABLE audit_trails(
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  id_rfq INT NOT NULL,
  username VARCHAR(255) NOT NULL,
  audit_trail TEXT CHARACTER SET utf8 NOT NULL,
  created_date DATETIME,
  PRIMARY KEY(id),
  FOREIGN KEY(id_rfq)
    REFERENCES rfq(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);

CREATE TABLE re_quote_audit_trails(
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  id_re_quote INT NOT NULL,
  username VARCHAR(255) NOT NULL,
  audit_trail TEXT CHARACTER SET utf8 NOT NULL,
  created_date DATETIME,
  PRIMARY KEY(id),
  FOREIGN KEY(id_re_quote)
    REFERENCES re_quotes(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);

CREATE TABLE re_quote_providers(
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  id_re_quote_item INT NOT NULL,
  provider VARCHAR(255) NOT NULL,
  price DECIMAL(20,2) NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(id_re_quote_item)
    REFERENCES re_quote_items(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);

CREATE TABLE re_quote_subitems(
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  id_re_quote_item INT NOT NULL,
  brand TEXT CHARACTER SET utf8 NOT NULL,
  brand_project TEXT CHARACTER SET utf8 NOT NULL,
  part_number TEXT CHARACTER SET utf8 NOT NULL,
  part_number_project TEXT CHARACTER SET utf8 NOT NULL,
  description TEXT CHARACTER SET utf8 NOT NULL,
  description_project TEXT CHARACTER SET utf8 NOT NULL,
  quantity INT NOT NULL,
  unit_price DECIMAL(20,2) NOT NULL,
  total_price DECIMAL(20,2) NOT NULL,
  comments TEXT CHARACTER SET utf8 NOT NULL,
  website VARCHAR(255) NOT NULL,
  additional DECIMAL(20,2) NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(id_re_quote_item)
    REFERENCES re_quote_items(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);

CREATE TABLE re_quote_subitem_providers(
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  id_re_quote_subitem INT NOT NULL,
  provider VARCHAR(255) NOT NULL,
  price DECIMAL(20,2) NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(id_re_quote_subitem)
    REFERENCES re_quote_subitems(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);

/*TRACKINGS*/

CREATE TABLE trackings(
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  id_item INT NOT NULL,
  quantity INT NOT NULL,
  tracking_number TEXT CHARACTER SET utf8 NOT NULL,
  delivery_date DATE NOT NULL,
  signed_by VARCHAR(255) NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(id_item)
    REFERENCES item(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);

CREATE TABLE trackings_subitems(
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  id_subitem INT NOT NULL,
  quantity INT NOT NULL,
  tracking_number TEXT CHARACTER SET utf8 NOT NULL,
  delivery_date DATE NOT NULL,
  signed_by VARCHAR(255) NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(id_subitem)
    REFERENCES subitems(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);

/*FULFILLMENT*/

CREATE TABLE fulfillment_items(
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  id_item INT NOT NULL,
  provider VARCHAR(255) NOT NULL,
  quantity INT NOT NULL,
  unit_cost DECIMAL(20,2) NOT NULL,
  other_cost DECIMAL(20,2) NOT NULL,
  real_cost DECIMAL(20,2) NOT NULL,
  payment_term VARCHAR(255) NOT NULL,
  net30_cc TINYINT,
  PRIMARY KEY(id),
  FOREIGN KEY(id_item)
    REFERENCES item(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);

CREATE TABLE fulfillment_subitems(
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  id_subitem INT NOT NULL,
  provider VARCHAR(255) NOT NULL,
  quantity INT NOT NULL,
  unit_cost DECIMAL(20,2) NOT NULL,
  other_cost DECIMAL(20,2) NOT NULL,
  real_cost DECIMAL(20,2) NOT NULL,
  payment_term VARCHAR(255) NOT NULL,
  net30_cc TINYINT,
  PRIMARY KEY(id),
  FOREIGN KEY(id_subitem)
    REFERENCES subitems(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);

CREATE TABLE fulfillment_services(
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  id_service INT NOT NULL,
  provider VARCHAR(255) NOT NULL,
  quantity INT NOT NULL,
  unit_cost DECIMAL(20,2) NOT NULL,
  other_cost DECIMAL(20,2) NOT NULL,
  real_cost DECIMAL(20,2) NOT NULL,
  payment_term VARCHAR(255) NOT NULL,
  PRIMARY KEY(id),
  FOREIGN KEY(id_service)
    REFERENCES services(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);

CREATE TABLE providers_list(
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  company_name VARCHAR(255) NOT NULL,
  PRIMARY KEY(id)
);

CREATE TABLE type_of_bids(
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  type_of_bid VARCHAR(255) NOT NULL,
  PRIMARY KEY(id)
);

CREATE TABLE payment_terms(
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  payment_term VARCHAR(255) NOT NULL,
  PRIMARY KEY(id)
);

CREATE TABLE type_of_contracts (
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  type_of_contract VARCHAR(255) NOT NULL,
  PRIMARY KEY(id)
);

INSERT INTO type_of_contracts (type_of_contract)
VALUES
('RFQ'),
('RFP Maintenance'),
('RFP Installation'),
('Professional Services'),
('Moving and Logistics');

CREATE TABLE tasks(
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  id_user INT NOT NULL,
  assigned_user INT NOT NULL,
  id_rfq INT,
  title VARCHAR(255) NOT NULL,
  message TEXT CHARACTER SET utf8,
  status VARCHAR(255),
  PRIMARY KEY(id),
  FOREIGN KEY(id_user)
    REFERENCES usuarios(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);

CREATE TABLE task_comments(
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  id_task INT NOT NULL,
  id_user INT NOT NULL,
  comment TEXT CHARACTER SET utf8 NOT NULL,
  created_at DATETIME,
  PRIMARY KEY(id),
  FOREIGN KEY(id_task)
    REFERENCES tasks(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);

CREATE TABLE fulfillment_audit_trails(
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  id_rfq INT NOT NULL,
  username VARCHAR(255) NOT NULL,
  audit_trail TEXT CHARACTER SET utf8 NOT NULL,
  created_date DATETIME,
  PRIMARY KEY(id),
  FOREIGN KEY(id_rfq)
    REFERENCES rfq(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);

ALTER TABLE rfq AUTO_INCREMENT = 300;
