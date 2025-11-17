-- =============================================
-- Base de datos: bdcarritocompras. Incluye la nueva columna idusuario en producto, ya que si no, no podía editar roles.
-- =============================================

CREATE DATABASE IF NOT EXISTS `bdcarritocompras` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `bdcarritocompras`;

-- ------------------------------------------------------
-- 1. compraestadotipo
-- ------------------------------------------------------
CREATE TABLE `compraestadotipo` (
  `idcompraestadotipo` INT NOT NULL,
  `cetdescripcion` VARCHAR(50) NOT NULL,
  `cetdetalle` VARCHAR(256) NOT NULL,
  PRIMARY KEY (`idcompraestadotipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `compraestadotipo` (`idcompraestadotipo`, `cetdescripcion`, `cetdetalle`) VALUES
(1, 'iniciada', 'cuando el usuario cliente inicia la compra de uno o más productos del carrito'),
(2, 'aceptada', 'cuando el usuario administrador da ingreso a una compra en estado iniciada'),
(3, 'enviada', 'cuando el usuario administrador envía la compra'),
(4, 'cancelada', 'compra cancelada por administrador o cliente (solo si está iniciada)');

-- ------------------------------------------------------
-- 2. rol
-- ------------------------------------------------------
CREATE TABLE `rol` (
  `idrol` BIGINT NOT NULL AUTO_INCREMENT,
  `rodescripcion` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`idrol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------
-- 3. usuario
-- ------------------------------------------------------
CREATE TABLE `usuario` (
  `idusuario` BIGINT NOT NULL AUTO_INCREMENT,
  `usnombre` VARCHAR(50) NOT NULL,
  `uspass` VARCHAR(255) NOT NULL,          -- hash de contraseña
  `usmail` VARCHAR(100) NOT NULL,
  `usdeshabilitado` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`idusuario`),
  UNIQUE KEY `usmail` (`usmail`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------
-- 4. usuariorol
-- ------------------------------------------------------
CREATE TABLE `usuariorol` (
  `idusuario` BIGINT NOT NULL,
  `idrol` BIGINT NOT NULL,
  PRIMARY KEY (`idusuario`,`idrol`),
  CONSTRAINT `fk_usuariorol_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON UPDATE CASCADE,
  CONSTRAINT `fk_usuariorol_rol` FOREIGN KEY (`idrol`) REFERENCES `rol` (`idrol`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------
-- 5. producto  <-- AQUÍ SE AGREGA idusuario
-- ------------------------------------------------------
CREATE TABLE `producto` (
  `idproducto` BIGINT NOT NULL AUTO_INCREMENT,
  `pronombre` VARCHAR(100) NOT NULL,
  `prodetalle` VARCHAR(512) NOT NULL,
  `procantstock` INT NOT NULL DEFAULT 0,
  `prodeshabilitado` TIMESTAMP NULL DEFAULT NULL,
  `idusuario` BIGINT NOT NULL,              -- ← NUEVA COLUMNA
  PRIMARY KEY (`idproducto`),
  KEY `fk_producto_usuario` (`idusuario`),
  CONSTRAINT `fk_producto_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------
-- 6. compra
-- ------------------------------------------------------
CREATE TABLE `compra` (
  `idcompra` BIGINT NOT NULL AUTO_INCREMENT,
  `cofecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `idusuario` BIGINT NOT NULL,
  PRIMARY KEY (`idcompra`),
  CONSTRAINT `fk_compra_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------
-- 7. compraestado
-- ------------------------------------------------------
CREATE TABLE `compraestado` (
  `idcompraestado` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `idcompra` BIGINT NOT NULL,
  `idcompraestadotipo` INT NOT NULL,
  `cefechaini` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cefechafin` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`idcompraestado`),
  CONSTRAINT `fk_compraestado_compra` FOREIGN KEY (`idcompra`) REFERENCES `compra` (`idcompra`) ON UPDATE CASCADE,
  CONSTRAINT `fk_compraestado_tipo` FOREIGN KEY (`idcompraestadotipo`) REFERENCES `compraestadotipo` (`idcompraestadotipo`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------
-- 8. compraitem
-- ------------------------------------------------------
CREATE TABLE `compraitem` (
  `idcompraitem` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `idproducto` BIGINT NOT NULL,
  `idcompra` BIGINT NOT NULL,
  `cicantidad` INT NOT NULL,
  PRIMARY KEY (`idcompraitem`),
  CONSTRAINT `fk_compraitem_compra` FOREIGN KEY (`idcompra`) REFERENCES `compra` (`idcompra`) ON UPDATE CASCADE,
  CONSTRAINT `fk_compraitem_producto` FOREIGN KEY (`idproducto`) REFERENCES `producto` (`idproducto`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------
-- 9. menu
-- ------------------------------------------------------
CREATE TABLE `menu` (
  `idmenu` BIGINT NOT NULL AUTO_INCREMENT,
  `menombre` VARCHAR(50) NOT NULL,
  `medescripcion` VARCHAR(124) NOT NULL,
  `idpadre` BIGINT NULL DEFAULT NULL,
  `medeshabilitado` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`idmenu`),
  CONSTRAINT `fk_menu_padre` FOREIGN KEY (`idpadre`) REFERENCES `menu` (`idmenu`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------------
-- 10. menurol
-- ------------------------------------------------------
CREATE TABLE `menurol` (
  `idmenu` BIGINT NOT NULL,
  `idrol` BIGINT NOT NULL,
  PRIMARY KEY (`idmenu`,`idrol`),
  CONSTRAINT `fk_menurol_menu` FOREIGN KEY (`idmenu`) REFERENCES `menu` (`idmenu`) ON UPDATE CASCADE,
  CONSTRAINT `fk_menurol_rol` FOREIGN KEY (`idrol`) REFERENCES `rol` (`idrol`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Base de datos actualizada con idusuario en producto, ya que si no, no podía asignar roles.
-- =============================================