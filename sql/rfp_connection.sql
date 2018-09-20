CREATE TABLE rfp_connection(
  id INT NOT NULL AUTO_INCREMENT UNIQUE,
  id_rfq INT NOT NULL,
  rfp TINYINT,
  PRIMARY KEY (id),
  FOREIGN KEY(id_rfq)
      REFERENCES rfq(id)
      ON UPDATE CASCADE
      ON DELETE RESTRICT
);
