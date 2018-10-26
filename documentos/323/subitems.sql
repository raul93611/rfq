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
