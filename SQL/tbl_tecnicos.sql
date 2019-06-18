USE satpro;
DROP TABLE IF EXISTS tbl_tecnicos;
CREATE TABLE tbl_tecnicos (
  idTecnico INT(4) NOT NULL AUTO_INCREMENT,
  nombresTecnico VARCHAR(40) NOT NULL,
  apellidosTecnico VARCHAR(40) NOT NULL,
  state TINYINT(1) NOT NULL,
  PRIMARY KEY(idTecnico)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO tbl_tecnicos VALUES (1, "Gerson", "Argueta", 1);