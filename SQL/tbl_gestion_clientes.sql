USE satpro;
DROP TABLE IF EXISTS tbl_gestion_clientes;
CREATE TABLE tbl_gestion_clientes (
  idGestion INT(6) NOT NULL AUTO_INCREMENT,
  codigoCliente INT(5) ZEROFILL DEFAULT NULL,
  fechaOrden DATE NOT NULL,
  /*tipoOrden VARCHAR(20) NOT NULL,*/
  saldo DOUBLE NOT NULL,
  diaCobro int(2) DEFAULT NULL,
  nombreCliente VARCHAR(50) NOT NULL,
  direccion VARCHAR(150) DEFAULT NULL,
  telefonos VARCHAR(25) NOT NULL,
  /*nodo VARCHAR(20) DEFAULT NULL,*/
  tipoServicio CHAR(1) NOT NULL,
  creadoPor VARCHAR(50) NOT NULL,
  PRIMARY KEY(idGestion),
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE tbl_ordenes_traslado AUTO_INCREMENT = 1;

/*TABLA USADA PARA GUARDAR LAS GESTIONES QUE TIENE CADA CLIENTE*/
USE satpro;
DROP TABLE IF EXISTS tbl_gestion;
CREATE TABLE tbl_gestion (
  idGestion INT(6) NOT NULL AUTO_INCREMENT,
  codigoCliente INT(5) ZEROFILL DEFAULT NULL,
  fechaOrden DATE NOT NULL,
  /*tipoOrden VARCHAR(20) NOT NULL,*/
  saldo DOUBLE NOT NULL,
  diaCobro int(2) DEFAULT NULL,
  nombreCliente VARCHAR(50) NOT NULL,
  direccion VARCHAR(150) DEFAULT NULL,
  telefonos VARCHAR(25) NOT NULL,
  /*nodo VARCHAR(20) DEFAULT NULL,*/
  tipoServicio CHAR(1) NOT NULL,
  creadoPor VARCHAR(50) NOT NULL,
  PRIMARY KEY(idGestion),
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE tbl_ordenes_traslado AUTO_INCREMENT = 1;