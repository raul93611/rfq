CREATE TABLE rfp(
    id INT NOT NULL AUTO_INCREMENT UNIQUE,
    id_user INT NOT NULL,
    designated_user INT NOT NULL,
    code VARCHAR(100) NOT NULL,
    type_of_bid VARCHAR(100) NOT NULL,
    completed TINYINT NOT NULL,
    completed_date DATE,
    total_cost DECIMAL(10,2),
    total_price DECIMAL(10,2),
    comments VARCHAR(100),
    payment_terms VARCHAR(100) NOT NULL,
    address TEXT CHARACTER SET utf8 NOT NULL,
    ship_to TEXT CHARACTER SET utf8 NOT NULL,
    ship_via VARCHAR(100) NOT NULL,
    taxes DECIMAL(10,2) NOT NULL,
    profit DECIMAL(10,2) NOT NULL,
    additional VARCHAR(100) NOT NULL,
    shipping VARCHAR(100) NOT NULL,
    shipping_cost DECIMAL(10,2) NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(id_user)
      REFERENCES usuarios(id)
      ON UPDATE CASCADE
      ON DELETE RESTRICT
);

CREATE TABLE item_rfp(
    id INT NOT NULL AUTO_INCREMENT UNIQUE,
    id_rfp INT NOT NULL,
    selected_provider INT NOT NULL,
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
    PRIMARY KEY(id),
    FOREIGN KEY(id_rfp)
      REFERENCES rfp(id)
      ON UPDATE CASCADE
      ON DELETE RESTRICT
);

CREATE TABLE provider_rfp(
    id INT NOT NULL AUTO_INCREMENT UNIQUE,
    id_item INT NOT NULL,
    provider VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY (id_item)
      REFERENCES item(id)
      ON UPDATE CASCADE
      ON DELETE RESTRICT
);
