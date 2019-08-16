USE satpro;
DROP TABLE IF EXISTS tbl_roles;
CREATE TABLE tbl_roles (
  idRol INT(2) NOT NULL AUTO_INCREMENT,
  nombreRol VARCHAR(30) NOT NULL,
  descripcion VARCHAR(50) DEFAULT NULL,
  PRIMARY KEY(idRol)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 INSERT INTO tbl_roles (idRol, nombreRol) VALUES(1, 'Administración');
 INSERT INTO tbl_roles (idRol, nombreRol) VALUES(2, 'Subgerencia');
 INSERT INTO tbl_roles (idRol, nombreRol) VALUES(3, 'Jefatura');
 INSERT INTO tbl_roles (idRol, nombreRol) VALUES(4, 'Informática');
 INSERT INTO tbl_roles (idRol, nombreRol) VALUES(5, 'Contabilidad');
 INSERT INTO tbl_roles (idRol, nombreRol) VALUES(6, 'Atención al cliente');
 INSERT INTO tbl_roles (idRol, nombreRol) VALUES(7, 'Técnico');
 INSERT INTO tbl_roles (idRol, nombreRol) VALUES(8, 'Vendedor');
 INSERT INTO tbl_roles (idRol, nombreRol) VALUES(9, 'Cobrador');
 INSERT INTO tbl_roles (idRol, nombreRol) VALUES(10, 'Mantenimiento');
 INSERT INTO tbl_roles (idRol, nombreRol) VALUES(11, 'Seguridad');