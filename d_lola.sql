-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-06-2024 a las 01:14:37
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `d_lola`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `ActualizarCliente` (IN `_idPersona` INT, IN `_nombre` VARCHAR(255), IN `_apellidos` VARCHAR(255), IN `_edad` DATE, IN `_idTipoDoc` INT, IN `_numDoc` VARCHAR(255), IN `_correo` VARCHAR(255), IN `_genero` VARCHAR(50), IN `_pais` VARCHAR(50), IN `_habilitado` TINYINT)   BEGIN
    UPDATE persona 
    SET cPerNombre = _nombre, 
        cPerApellidos = _apellidos, 
        cPerEdad = _edad, 
        iTipoDocID = _idTipoDoc, 
        tPerNumDoc = _numDoc, 
        cPerCorreo = _correo, 
        cPerGenero = _genero, 
        cPerPais = _pais 
    WHERE cPerID = _idPersona;

	UPDATE cliente 
    SET cCliHabilitado=_habilitado
    WHERE cPerID = _idPersona;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ActualizarTrabajador` (IN `_idPersona` INT, IN `_nombre` TEXT, IN `_apellidos` TEXT, IN `_edad` DATE, IN `_idTipoDoc` INT, IN `_numDoc` TEXT, IN `_correo` TEXT, IN `_genero` TEXT, IN `_pais` TEXT, IN `_cargoID` INT, IN `_usuario` TEXT, IN `_clave` TEXT, IN `_sueldo` FLOAT)   BEGIN
    UPDATE persona 
    SET cPerNombre = _nombre, 
        cPerApellidos = _apellidos, 
        cPerEdad = _edad, 
        iTipoDocID = _idTipoDoc, 
        tPerNumDoc = _numDoc, 
        cPerCorreo = _correo, 
        cPerGenero = _genero,  
        cPerPais = _pais 
    WHERE cPerID = _idPersona;

    UPDATE trabajador 
    SET iCarID = _cargoID, 
        fTraSueldo = _sueldo 
    WHERE cPerID = _idPersona;

    IF _usuario IS NOT NULL AND _clave IS NOT NULL THEN
        UPDATE usuario 
        SET cUserNUsuario = _usuario, 
            cUserClave = _clave 
        WHERE cPerID = _idPersona;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `actualizarUsuarioActivo` (IN `p_Mandar` TEXT, IN `p_Clave` TEXT)   BEGIN
UPDATE usuario u
INNER JOIN persona p ON p.cPerID = u.cPerID
INNER JOIN trabajador t ON t.cPerID = p.cPerID
SET
    u.cUsuActivo = 1
WHERE
    (p.cPerCorreo = p_Mandar OR u.cUserNUsuario = p_Mandar) AND
    u.cUserClave = p_Clave;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `BloquearUsuario` (IN `p_Mandar` TEXT, IN `p_Clave` TEXT)   BEGIN
UPDATE usuario u
INNER JOIN persona p ON p.cPerID = u.cPerID
INNER JOIN trabajador t ON t.cPerID = p.cPerID
SET
    u.cUsuHabilitado = 0
WHERE
    (p.cPerCorreo = p_Mandar OR u.cUserNUsuario = p_Mandar) AND
    u.cUserClave = p_Clave;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `desactivarUsuario` (IN `p_Mandar` TEXT, IN `p_Clave` TEXT)   BEGIN
UPDATE usuario u
INNER JOIN persona p ON p.cPerID = u.cPerID
INNER JOIN trabajador t ON t.cPerID = p.cPerID
SET
    u.cUsuActivo = 0
WHERE
    (p.cPerCorreo = p_Mandar OR u.cUserNUsuario = p_Mandar) AND
    u.cUserClave = p_Clave;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `IniciarSesionTrabajador` (IN `p_Mandar` VARCHAR(255), IN `p_Clave` VARCHAR(255))   BEGIN
    DECLARE existeCorreo INT;
    DECLARE existeUsuario INT;
    DECLARE existeClave INT;
    DECLARE existeTrabajador INT;
    DECLARE usuarioInhabilitado INT;

    SELECT COUNT(*) INTO existeCorreo
    FROM usuariostrabajadores UT
    WHERE UT.cPerCorreo = p_Mandar;

    IF existeCorreo = 0 THEN
        SELECT COUNT(*) INTO existeUsuario
        FROM usuariostrabajadores UT
        WHERE UT.cUserNUsuario = p_Mandar;
    END IF;

    SELECT COUNT(*) INTO existeClave
    FROM usuariostrabajadores UT
    WHERE UT.cUserClave = p_Clave;

    SELECT COUNT(*) INTO usuarioInhabilitado
    FROM usuariostrabajadores UT
    WHERE (UT.cPerCorreo = p_Mandar OR UT.cUserNUsuario = p_Mandar) AND UT.cUsuHabilitado = 0;

    IF usuarioInhabilitado > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'USUARIO INHABILITADO DEL SISTEMA';
    END IF;

    IF existeCorreo + existeUsuario + existeClave = 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'ACCESO DENEGADO';
    END IF;

    IF existeCorreo > 0 OR existeUsuario > 0 THEN
        IF existeClave > 0 THEN
            SELECT t.cTraID, p.cPerNombre, p.cPerApellidos, p.cPerEdad, p.cPerCorreo, p.cPerGenero, p.cPerPais, u.cUserNUsuario, u.cUserClave, c.iCarID, t.fTraSueldo 
            FROM persona p 
            INNER JOIN trabajador t ON t.cPerID = p.cPerID 
            INNER JOIN usuario u ON u.cPerID = p.cPerID 
            INNER JOIN cargo c ON t.iCarID = c.iCarID
            WHERE (p.cPerCorreo = p_Mandar OR u.cUserNUsuario = p_Mandar) AND u.cUserClave = p_Clave;
        ELSE
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'CLAVE NO EXISTE';
        END IF;    
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'USUARIO O CORREO ELECTRONICO NO EXISTE';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ObtenerPedidosPorID` (IN `pedidoID` INT)   BEGIN
    SELECT p.cPedID AS IDPedido, 
           pl.cPlaID AS IDPlato, 
           c.cCatNombre AS Categoria, 
           pl.cPlaNombre AS NombrePlato, 
           dp.iDepCantidad AS Cantidad,
           pl.cPlaPrecio AS Precio,
           (dp.iDepCantidad * pl.cPlaPrecio) AS PrecioFinal
    FROM detallepedido dp 
    INNER JOIN pedido p ON dp.cPedID=p.cPedID 
    INNER JOIN platos pl ON dp.cPlaID=pl.cPlaID 
    INNER JOIN categoria c ON pl.cCatID=c.cCatID
    WHERE p.cPedID = pedidoID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ObtenerVentasPorID` (IN `_idVenta` INT)   BEGIN
    SELECT dt.iDetID, dt.iVenID, p.cPlaNombre, dt.iDetCantidad, p.cPlaPrecio FROM detalleventa dt INNER JOIN platos p ON dt.iPlaID = p.cPlaID WHERE dt.iVenID = _idVenta;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RecuperarClienteID` (IN `_idCliente` INT)   BEGIN
select `p`.`cPerID` AS `cPerID`,`c`.`cCliID` AS `cCliID`,`p`.`cPerNombre` AS `cPerNombre`,`p`.`cPerApellidos` AS `cPerApellidos`,`p`.`cPerEdad` AS `cPerEdad`,`p`.`iTipoDocID` AS `iTipoDocID`,`p`.`tPerNumDoc` AS `tPerNumDoc`,`p`.`cPerCorreo` AS `cPerCorreo`,`p`.`cPerGenero` AS `cPerGenero`,`p`.`cPerPais` AS `cPerPais`, `c`.`cCliHabilitado` AS `cCliHabilitado` from (`d_lola`.`persona` `p` right join `d_lola`.`cliente` `c` on(`c`.`cPerID` = `p`.`cPerID`)) where `c`.`cCliID` = _idCliente;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RecuperarTrabajador` ()   select `p`.`cPerID` AS `cPerID`,`t`.`cTraID` AS `cTraID`,`u`.`cUserID` AS `cUserID`,`p`.`cPerNombre` AS `cPerNombre`,`p`.`cPerApellidos` AS `cPerApellidos`,`p`.`iTipoDocID` AS `iTipoDocID`,`p`.`tPerNumDoc` AS `tPerNumDoc`,`p`.`cPerCorreo` AS `cPerCorreo`,`p`.`cPerGenero` AS `cPerGenero`,`p`.`cPerPais` AS `cPerPais`,`u`.`cUserNUsuario` AS `cUserNUsuario`,`u`.`cUserClave` AS `cUserClave`,`t`.`fTraSueldo` AS `fTraSueldo`,`u`.`cUserRol` AS `cUserRol` from ((`d_lola`.`persona` `p` join `d_lola`.`usuario` `u` on(`u`.`cPerID` = `p`.`cPerID`)) join `d_lola`.`trabajador` `t` on(`t`.`cPerID` = `p`.`cPerID`)) where `u`.`cUserRol` <> 'normal'$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RecuperarTrabajadorPorID` (IN `usuario_id` INT)   BEGIN
    SELECT 
        p.cPerID AS cPerID,
        t.cTraID AS cTraID,
        u.cUserID AS cUserID,
        p.cPerNombre AS cPerNombre,
        p.cPerApellidos AS cPerApellidos,
        p.cPerEdad AS cPerEdad,
        p.iTipoDocID AS iTipoDocID,
        p.tPerNumDoc AS tPerNumDoc,
        p.cPerCorreo AS cPerCorreo,
        p.cPerGenero AS cPerGenero,
        p.cPerPais AS cPerPais,
        u.cUserNUsuario AS cUserNUsuario,
        u.cUserClave AS cUserClave,
        t.fTraSueldo AS fTraSueldo,
        t.iCarID AS iCarID,
        u.cUserRol AS cUserRol 
    FROM 
        persona p 
    JOIN 
        usuario u ON u.cPerID = p.cPerID
    JOIN 
        trabajador t ON t.cPerID = p.cPerID
    WHERE 
        u.cUserRol <> 'normal' 
        AND u.cUserID = usuario_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `recuperar_clientes_por_credenciales` (IN `p_Mandar` VARCHAR(255), IN `p_Clave` VARCHAR(255))   BEGIN
    DECLARE existeCorreo INT;
    DECLARE existeUsuario INT;
    DECLARE existeClave INT;

    SELECT COUNT(*) INTO existeCorreo
    FROM persona
    WHERE cPerCorreo = p_Mandar;

    IF existeCorreo = 0 THEN
        SELECT COUNT(*) INTO existeUsuario
        FROM usuario
        WHERE cUserNUsuario = p_Mandar;
    END IF;
    
    SELECT COUNT(*) INTO existeClave
    FROM usuario WHERE usuario.cUserClave = p_Clave;
    
    IF existeCorreo+existeUsuario+existeClave = 0 THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'ERROR: ACCESO DENEGADO';
    END IF;

    IF existeCorreo > 0 OR existeUsuario > 0 THEN
		IF existeClave > 0 THEN
            SELECT 
            p.cPerID AS cPerID,
            u.cUserID AS cUserID,
            p.cPerNombre AS cPerNombre,
            p.cPerApellidos AS cPerApellidos,
            p.cPerEdad AS cPerEdad,
            p.cPerCorreo AS cPerCorreo,
            p.cPerGenero AS cPerGenero,
            p.cPerPais AS cPerPais,
            u.cUserNUsuario AS cUserNUsuario,
            u.cUserClave AS cUserClave,
            u.cUserRol AS cUserRol
            FROM persona p INNER JOIN usuario u ON
            p.cPerID=u.cPerID
            
            WHERE (p.cPerCorreo = p_Mandar OR u.cUserNUsuario = p_Mandar) AND u.cUserClave = p_Clave;
        ELSE
        	SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Clave no existe';
        END IF;    
    ELSE
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Usuario o correo electrónico no existe';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RegistrarCargo` (IN `_nombreCargo` TEXT)   BEGIN
	INSERT INTO cargo (tCarNombre) VALUES (_nombreCargo);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RegistrarCliente` (IN `_nombre` VARCHAR(255), IN `_apellidos` VARCHAR(255), IN `_edad` DATE, IN `_idTipoDoc` INT, IN `_numDoc` VARCHAR(255), IN `_correo` VARCHAR(255), IN `_genero` VARCHAR(50), IN `_pais` VARCHAR(50))   BEGIN
    DECLARE idPersona INT;
    INSERT INTO Persona (cPerNombre, cPerApellidos, cPerEdad, iTipoDocID, tPerNumDoc, cPerCorreo, cPerGenero, cPerPais)
    VALUES (_nombre, _apellidos, _edad, _idTipoDoc, _numDoc, _correo, _genero, _pais);
    SET idPersona = LAST_INSERT_ID();
    INSERT INTO cliente (cPerID,cCliTipoCliente) VALUES (idPersona,'IDENTIFICADO');
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RegistrarClienteGenerico` ()   BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'An error occurred, transaction rolled back';
    END;
    START TRANSACTION;
    INSERT INTO cliente (cCliTipoCliente) VALUES ('CLIENTE EN RESTAURANTE');
    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RegistrarPersonaYUsuario` (IN `pNombre` VARCHAR(50), IN `pApellidos` VARCHAR(50), IN `pEdad` DATE, IN `pCorreo` VARCHAR(50), IN `pNombreUsuario` VARCHAR(255), IN `pClave` VARCHAR(255), IN `pGenero` VARCHAR(50), IN `pPais` VARCHAR(255), IN `pRol` VARCHAR(100))   BEGIN
    DECLARE lastPersonID INT;
    DECLARE usuario VARCHAR(100);
    
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
    BEGIN
        SHOW ERRORS;
    END;

    -- Registrar la persona
    INSERT INTO persona (cPerNombre, cPerApellidos, cPerEdad, cPerCorreo, cPerGenero, cPerPais)
    VALUES (pNombre, pApellidos, pEdad, pCorreo, pGenero, pPais);

    -- Obtener el ID de la persona recién registrada
    SET lastPersonID = LAST_INSERT_ID();

    -- Validar que lastPersonID sea mayor a 0
    IF lastPersonID > 0 THEN
        -- Registrar el usuario con el ID de la persona como clave foránea
        INSERT INTO usuario (cPerID, cUserNUsuario, cUserClave, cUserRol) VALUES (lastPersonID, pNombreUsuario, pClave, pRol);
    ELSE
        -- Manejar la situación donde lastPersonID no es mayor a 0
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: lastPersonID no es válido.';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `RegistrarTrabajador` (IN `_nombre` VARCHAR(255), IN `_apellidos` VARCHAR(255), IN `_edad` DATE, IN `_idTipoDoc` INT, IN `_numDoc` VARCHAR(255), IN `_correo` VARCHAR(255), IN `_genero` VARCHAR(50), IN `_pais` VARCHAR(50), IN `_cargoID` INT, IN `_usuario` VARCHAR(250), IN `_clave` VARCHAR(250), IN `_sueldo` FLOAT)   BEGIN
    DECLARE idPersona INT;
    DECLARE cargoTrabajador TEXT;
    INSERT INTO Persona (cPerNombre, cPerApellidos, cPerEdad, iTipoDocID, tPerNumDoc, cPerCorreo, cPerGenero, cPerPais)
    VALUES (_nombre, _apellidos, _edad, _idTipoDoc, _numDoc, _correo, _genero, _pais);
    SET idPersona = LAST_INSERT_ID();
    INSERT INTO trabajador (cPerID, iCarID, fTraSueldo)
    VALUES (idPersona, _cargoID, _sueldo);
    SELECT tCarNombre INTO cargoTrabajador FROM cargo WHERE iCarID = _cargoID;
    INSERT INTO usuario (cPerID, cUserNUsuario, cUserClave, cUsuActivo, cUsuHabilitado, cUserRol)
    VALUES (idPersona, _usuario, _clave, 0, 1, cargoTrabajador);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ReiniciarIDTablaPedido` ()   BEGIN
    -- Resetear el contador de identidad para la tabla pedido
    ALTER TABLE pedido AUTO_INCREMENT = 1;
    -- Resetear el contador de identidad para la tabla detallepedido
    ALTER TABLE detallepedido AUTO_INCREMENT = 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `VerificarEstadoCaja` ()  DETERMINISTIC BEGIN
    DECLARE estado_caja VARCHAR(255);
    
    -- Buscar una caja con estado diferente a 'abierta'
    SELECT estado INTO estado_caja
    FROM caja
    WHERE estado <> 'abierta'
    LIMIT 1;

    IF estado_caja IS NOT NULL THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Se encontró al menos una caja con estado diferente a "abierta".';
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE `caja` (
  `id_caja` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `fecha_apertura` datetime NOT NULL,
  `fecha_cierre` datetime NOT NULL,
  `estado` enum('abierta','cerrada') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `caja`
--

INSERT INTO `caja` (`id_caja`, `nombre`, `fecha_apertura`, `fecha_cierre`, `estado`) VALUES
(1, 'CAJA 001 - PRINCIPAL', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'abierta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `iCarID` int(11) NOT NULL,
  `tCarNombre` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cargo`
--

INSERT INTO `cargo` (`iCarID`, `tCarNombre`) VALUES
(1, 'Administrador'),
(2, 'Cajero'),
(3, 'Mozo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `cCatID` int(11) NOT NULL,
  `cCatNombre` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`cCatID`, `cCatNombre`) VALUES
(1, 'Desayunos'),
(2, 'Jugos'),
(3, 'Almuerzos'),
(4, 'Sandwiches'),
(5, 'Menu Economico'),
(6, 'Menu Ejecutivo'),
(7, 'Bebidas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

CREATE TABLE `cliente` (
  `cCliID` int(11) NOT NULL,
  `cPerID` int(11) DEFAULT NULL,
  `cCliTipoCliente` enum('CLIENTE EN RESTAURANTE','IDENTIFICADO') DEFAULT NULL,
  `cCliHabilitado` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `cliente`
--

INSERT INTO `cliente` (`cCliID`, `cPerID`, `cCliTipoCliente`, `cCliHabilitado`) VALUES
(1, 5, 'IDENTIFICADO', 1),
(2, 7, 'IDENTIFICADO', 1),
(3, 9, 'IDENTIFICADO', 1),
(4, 10, 'IDENTIFICADO', 1),
(5, 11, 'IDENTIFICADO', 1),
(6, 12, 'IDENTIFICADO', 1),
(7, 13, 'IDENTIFICADO', 1),
(8, 14, 'IDENTIFICADO', 1),
(9, 19, 'IDENTIFICADO', 1),
(10, 20, 'IDENTIFICADO', 1),
(11, 27, 'IDENTIFICADO', 1),
(12, 28, 'IDENTIFICADO', 1),
(13, 38, 'IDENTIFICADO', 1),
(14, 40, 'IDENTIFICADO', 1),
(16, NULL, 'CLIENTE EN RESTAURANTE', 1),
(17, 55, 'IDENTIFICADO', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallecaja`
--

CREATE TABLE `detallecaja` (
  `iDcID` int(11) NOT NULL,
  `iCajaID` int(11) NOT NULL,
  `dDcIngTotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `dDcDevoluciones` decimal(10,2) NOT NULL DEFAULT 0.00,
  `dDcPrestamos` decimal(10,2) NOT NULL DEFAULT 0.00,
  `dDcGastos` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallepagos`
--

CREATE TABLE `detallepagos` (
  `cDetPagoID` int(11) NOT NULL,
  `iVenID` int(11) NOT NULL,
  `cPagoID` int(11) NOT NULL,
  `fDetPagCantidad` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detallepedido`
--

CREATE TABLE `detallepedido` (
  `iDepID` int(11) NOT NULL,
  `cPedID` int(11) NOT NULL,
  `cPlaID` int(11) NOT NULL,
  `iDepCantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detallepedido`
--

INSERT INTO `detallepedido` (`iDepID`, `cPedID`, `cPlaID`, `iDepCantidad`) VALUES
(1, 30, 27, 1),
(2, 31, 3, 1),
(3, 32, 5, 9),
(4, 33, 17, 2),
(5, 33, 72, 2);

--
-- Disparadores `detallepedido`
--
DELIMITER $$
CREATE TRIGGER `trg_DisminuirCantidadProducto` AFTER INSERT ON `detallepedido` FOR EACH ROW BEGIN
    UPDATE platos
    SET cPlaCantidad = cPlaCantidad - NEW.iDepCantidad
    WHERE cPlaID = NEW.cPlaID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalleventa`
--

CREATE TABLE `detalleventa` (
  `iDetID` int(11) NOT NULL,
  `iVenID` int(11) NOT NULL,
  `iPlaID` int(11) NOT NULL,
  `iDetCantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `cPagoID` int(11) NOT NULL,
  `cPagoTipo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pago`
--

INSERT INTO `pago` (`cPagoID`, `cPagoTipo`) VALUES
(1, 'YAPE'),
(2, 'EFECTIVO'),
(3, 'TARJETA'),
(4, 'PLIN'),
(5, 'BCP'),
(6, 'INTERBANK');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `cPedID` int(11) NOT NULL,
  `cCliID` int(11) DEFAULT NULL,
  `cTraID` int(11) DEFAULT NULL,
  `cPedTotal` int(11) NOT NULL DEFAULT 0,
  `dPedFecha` datetime DEFAULT current_timestamp(),
  `cPedEstado` enum('EN_PROCESO_VENTA','ANULADO','PAGADO') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`cPedID`, `cCliID`, `cTraID`, `cPedTotal`, `dPedFecha`, `cPedEstado`) VALUES
(30, 4, 13, 2, '2024-05-29 22:46:40', 'EN_PROCESO_VENTA'),
(31, 16, 13, 6, '2024-05-29 23:21:08', 'EN_PROCESO_VENTA'),
(32, 16, 13, 54, '2024-05-30 00:24:51', 'EN_PROCESO_VENTA'),
(33, 16, 8, 20, '2024-05-30 16:01:07', 'EN_PROCESO_VENTA');

--
-- Disparadores `pedido`
--
DELIMITER $$
CREATE TRIGGER `trg_EvitarStockMayor` BEFORE INSERT ON `pedido` FOR EACH ROW BEGIN
    DECLARE stock INT;
    DECLARE cantidad INT;
    DECLARE plato_id INT;
    DECLARE finished INTEGER DEFAULT 0;
    DECLARE cur CURSOR FOR 
        SELECT dp.cPlaID, dp.iDepCantidad
        FROM detallepedido dp
        WHERE dp.cPedID = NEW.cPedID;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1;

    -- Abrir el cursor
    OPEN cur;
    
    -- Bucle para recorrer el cursor
    read_loop: LOOP
        FETCH cur INTO plato_id, cantidad;
        IF finished THEN
            LEAVE read_loop;
        END IF;

        -- Obtener el stock del plato
        SELECT cPlaCantidad INTO stock
        FROM platos
        WHERE cPlaID = plato_id;

        -- Verificar si la cantidad solicitada es mayor al stock disponible
        IF cantidad > stock THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'No se puede insertar el pedido porque la cantidad de uno de los platos es mayor al stock disponible';
        END IF;
    END LOOP;

    -- Cerrar el cursor
    CLOSE cur;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_VerificarEstadoCaja` BEFORE INSERT ON `pedido` FOR EACH ROW BEGIN
    DECLARE cajas_abiertas INT;
    DECLARE mensaje_error VARCHAR(255);

    -- Contar el número de cajas abiertas
    SELECT COUNT(*) INTO cajas_abiertas
    FROM caja
    WHERE estado = 'abierta';

    -- Verificar si hay al menos una caja abierta
    IF cajas_abiertas = 0 THEN
        SET mensaje_error = 'No se puede realizar el pedido porque no hay cajas abiertas.';
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = mensaje_error;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `cPerID` int(11) NOT NULL,
  `cPerNombre` varchar(50) DEFAULT NULL,
  `cPerApellidos` varchar(50) DEFAULT NULL,
  `cPerEdad` date DEFAULT NULL,
  `iTipoDocID` int(11) DEFAULT NULL,
  `tPerNumDoc` text DEFAULT NULL,
  `cPerCorreo` varchar(50) DEFAULT NULL,
  `cPerGenero` varchar(50) DEFAULT NULL,
  `cPerPais` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`cPerID`, `cPerNombre`, `cPerApellidos`, `cPerEdad`, `iTipoDocID`, `tPerNumDoc`, `cPerCorreo`, `cPerGenero`, `cPerPais`) VALUES
(5, 'Juan Carlos', 'Diaz Larios', '1989-06-23', 1, '70745063', 'juanxix@gmail.com', 'M', 'PERÚ'),
(7, 'Wilder', 'Romero Fernandez', '1972-04-18', 1, '64750803', 'wilrofer@leoncioprado.edu.pe', 'M', 'PERÚ'),
(9, 'Romel', 'Linares Rubio', '1972-04-18', 1, '54802688', 'lrubioromel@uss.edu.pe', 'M', 'PERÚ'),
(10, 'Jhair', 'Ynoñan Barreto', '2002-08-10', 1, '79761032', 'ybarretojoi@uss.edu.pe', 'M', 'PERÚ'),
(11, 'Cesia', 'Fernandez Guevara', '1999-02-02', 1, '34397104', 'fguevaracn@uss.edu.pe', 'F', 'PERÚ'),
(12, 'David', 'Bernal Suclupe', '2003-10-10', 1, '32702425', 'bsuclupedavid@uss.edu.pe', 'M', 'PERÚ'),
(13, 'Pool', 'Deza Millones', '2000-02-01', 1, '60320816', 'dmillonesp@uss.edu.pe', 'M', 'PERÚ'),
(14, 'Porfirio Nervis', 'Sanchez Mundaca', '2000-01-01', 1, '3496812', 'smundacaporfirio@uss.edu.pe', 'M', 'PERÚ'),
(19, 'Fabiana Arleth', 'Zurita Chunga', '2003-01-01', 1, '36521615', 'zchungafa@gmail.com', 'F', 'PERÚ'),
(20, 'Percy Javier', 'Celis Bravo', '0000-00-00', 1, '72117643', 'percebra@uss.edu.pe', 'M', 'PERÚ'),
(21, 'Nervis', 'Sanchez Mundaca', '2000-10-10', 1, '13345678', 'smnervis@uss.edu.pe', 'M', 'Perú'),
(23, 'Eduardo', 'Hernandez', '2003-10-10', 1, '51023377', 'hdavilaedu@uss.edu.pe', 'M', 'Perú'),
(24, 'Carlos', 'Larios', '1989-06-23', 1, '38763959', 'juanxix@gmail.com', 'M', 'Perú'),
(25, 'Javier', 'Bravo', '1972-10-10', 1, '40749667', 'percebra@crece.uss.edu.pe', 'M', 'Perú'),
(26, 'Percy', 'Bravo', '1972-10-10', 1, '87456461', 'percycelisbravo@gmail.com', 'M', 'Perú'),
(27, 'Isrrael', 'Olano Sandoval', '2003-10-10', 1, '15033310', 'osandovalisrra@uss.edu.pe', 'M', 'PERÚ'),
(28, 'Felipe', 'Serquen Farro', '1972-10-10', 1, '12797169', 'sfarrofel@uss.edu.pe', 'M', 'PERÚ'),
(38, 'Jose Alberto', 'Diaz Vargas', '1965-05-28', 1, '16411023', 'albert_65@outlook.com', 'M', 'PERÚ'),
(40, 'Romel', 'Linaress', '2000-01-10', 1, '24502582', 'rlinares@uss.edu.pe', 'M', 'PERÚ'),
(41, 'Jose Manuel', 'Diaz Larios', '2004-01-05', 1, '72927825', 'joma200414@gmail.com', 'M', 'PERÚ'),
(42, 'Jose Luis', 'Diaz Polo', '2006-03-31', 1, '76543291', 'joseluisdiazpolo@gmail.com', 'M', 'PERÚ'),
(43, 'Junior', 'Cachay', '1986-06-11', 1, '12341230', 'cmacojuniore@uss.edu.pe', 'M', 'PERÚ'),
(45, 'Jose Darwin', 'Santamaria Chapoñan', '1983-10-10', 1, '32312312', 'schaponanjs@uss.edu.pe', 'M', 'PERÚ'),
(46, 'Jimena', 'Palomino Malca', '1985-10-10', 1, '98761234', 'jpalominomalca@gmail.com', 'F', 'PERÚ'),
(47, 'Jose Eduardo', 'Medianero Bernal', '2003-08-12', 1, '45567842', 'mbje@gmail.com', 'M', 'PERÚ'),
(49, 'Juan', 'Inga Campos', '0000-00-00', 1, '23121289', 'icamposj@uss.edu.pe', 'M', 'Perú'),
(50, 'Edward Smith', 'More Purihuaman', '0000-00-00', 1, '11223344', 'mpurihuamanedwa@uss.edu.pe', 'M', 'Perú'),
(51, 'Jhon Franklin', 'Ramos Ortiz', '0000-00-00', 1, '44332233', 'rortizjhonfranklin@uss.edu.pe', 'M', 'Perú'),
(55, 'Enrique', 'Asenjo', '0000-00-00', 1, '99998888', 'enrique@gmail.com', 'M', 'PERÚ'),
(57, 'Jhair Orlando Imanol', 'Ynoñan', '2002-08-23', 1, '75866195', 'joi@gmail.com', 'M', 'Perú');

--
-- Disparadores `persona`
--
DELIMITER $$
CREATE TRIGGER `trg_EvitarCorreoDuplicados` BEFORE INSERT ON `persona` FOR EACH ROW BEGIN
    DECLARE correoExiste INT;
    
    SELECT COUNT(cPerCorreo) INTO correoExiste FROM persona 
    WHERE cPerCorreo = NEW.cPerCorreo;
    
    IF correoExiste > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'ERROR DE REGISTRO DE CORREO: YA EXISTE UNO IGUAL';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_IngresarDocumento` BEFORE INSERT ON `persona` FOR EACH ROW BEGIN
    IF NEW.iTipoDocID = 1 THEN
        IF LENGTH(NEW.tPerNumDoc) != 8 THEN
            SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error: El número de documento para el tipo de persona 1 debe tener exactamente 8 caracteres';
        END IF;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_LongitudNumeroDNI` BEFORE INSERT ON `persona` FOR EACH ROW BEGIN
    DECLARE tipo_doc_name TEXT;
    SELECT tTipoDocNombre INTO tipo_doc_name
    FROM tipodocumento
    WHERE iTipoDocID = NEW.iTipoDocID;
    IF tipo_doc_name = 'DNI' AND LENGTH(NEW.tPerNumDoc) < 8 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'El número de documento DNI debe tener al menos 8 dígitos';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `platos`
--

CREATE TABLE `platos` (
  `cPlaID` int(11) NOT NULL,
  `cCatID` int(11) NOT NULL,
  `cPlaNombre` text NOT NULL,
  `cPlaCantidad` int(11) NOT NULL,
  `cPlaPrecio` decimal(10,0) NOT NULL,
  `cPlaDescripcion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `platos`
--

INSERT INTO `platos` (`cPlaID`, `cCatID`, `cPlaNombre`, `cPlaCantidad`, `cPlaPrecio`, `cPlaDescripcion`) VALUES
(1, 1, 'Batido de fresa', 1000, 6, 'Delicioso batido de fresa.'),
(2, 1, 'Crumble de manzana', 1000, 6, 'Crumble de manzana con un toque especial.'),
(3, 1, 'Galletas de almendra', 999, 6, 'Galletas de almendra con un toque único.'),
(4, 1, 'Huevos revueltos', 1000, 6, 'Huevos revueltos preparados a la perfección.'),
(5, 1, 'Pancakes', 991, 6, 'Pancakes esponjosos y deliciosos.'),
(6, 1, 'Torta Kiwi con fresas', 1000, 6, 'Torta de kiwi con fresas para un desayuno especial.'),
(7, 1, 'Tortitas de avena con platano', 1000, 6, 'Tortitas de avena con platano, una opción saludable.'),
(8, 1, 'Tostadas con palta', 1000, 6, 'Tostadas con palta, una combinación deliciosa.'),
(9, 1, 'Yogurt de avellanas y vainilla', 1000, 6, 'Yogurt de avellanas y vainilla, una opción refrescante.'),
(10, 2, 'Piña', 1000, 6, 'Disfruta de la frescura tropical con nuestro jugo de piña. Cada sorbo te transportará a las cálidas playas con su sabor jugoso y ligeramente ácido.'),
(11, 2, 'Papaya', 1000, 6, 'Sumérgete en la dulzura natural de nuestro jugo de papaya. La suavidad de la papaya madura se combina perfectamente en esta bebida refrescante.'),
(12, 2, 'Fresa', 1000, 6, 'Experimenta la explosión de sabor con nuestro jugo de fresa. Cada gota está cargada con la dulzura intensa y el aroma fresco de las fresas maduras.'),
(13, 2, 'Surtido papaya + piña', 1000, 10, 'Descubre una mezcla exquisita con nuestro jugo de papaya y piña. La combinación de la dulzura de la papaya y la acidez refrescante de la piña crea una experiencia única.'),
(14, 2, 'Surtido plátano + papaya', 1000, 10, 'Deléitate con la armonía de sabores en nuestro jugo de plátano y papaya. La cremosidad del plátano se fusiona con la suavidad de la papaya para una experiencia indulgente.'),
(15, 2, 'Surtido manzana + moras', 1000, 10, 'Explora la mezcla equilibrada de nuestro jugo de manzana con moras. La frescura de la manzana se complementa con la riqueza y el toque agridulce de las moras.'),
(16, 3, 'Tallarines verdes', 1000, 10, 'Deléitate con nuestra especialidad de tallarines verdes. Una exquisita combinación de pasta fresca y una suave salsa de espinacas con queso parmesano. ¡Una explosión de sabor en cada bocado!'),
(17, 3, 'Ají de gallina', 997, 10, 'Saborea la auténtica tradición peruana con nuestro Ají de gallina. Un platillo rico y cremoso, con pechuga de pollo desmenuzada y bañada en una deliciosa salsa de ají amarillo.'),
(18, 3, 'Arroz con pato', 1000, 10, 'Prueba la exquisitez de nuestro Arroz con pato, un plato peruano que combina arroz graneado con tierno y jugoso pato cocido a la perfección. ¡Una experiencia culinaria única!'),
(19, 3, 'Aeropuerto', 1000, 10, 'Viaja por sabores con nuestro Aeropuerto, un plato que ofrece una mezcla de carnes, papas fritas, arroz y huevo. ¡Una experiencia completa que te dejará satisfecho!'),
(20, 3, 'Pollo broaster', 1000, 10, 'Disfruta de nuestro Pollo broaster, crujiente por fuera y jugoso por dentro. Acompañado de papas fritas y ensalada fresca. ¡Una deliciosa opción para satisfacer tus antojos!'),
(21, 3, 'Bisteck a lo pobre', 1000, 10, 'El bistec a lo pobre es un plato tradicionalmente sabroso y reconfortante que destaca por su sencillez y delicioso sabor. Se trata de una preparación de carne de res, generalmente bistec, que se cocina de manera simple pero con resultados irresistibles.'),
(22, 3, 'Seco de cabrito', 999, 20, 'Cabrito cocido a fuego lento en una salsa espesa de chicha de jora, ajíes, hierbas y especias. Suele acompañarse con arroz y frijoles.'),
(23, 3, 'Causa lambayecana', 1000, 15, 'Incorpora ingredientes locales como mariscos, cangrejo o pescado fresco. La causa es una especie de pastel de papa amarilla relleno.'),
(24, 3, 'Cabrito a la norteña', 1000, 20, 'El cabrito es adobado con hierbas y especias antes de cocinarse lentamente.'),
(25, 3, 'Chinguirito', 1000, 20, 'Consiste en tiras de pescado seco, marinadas en limón y mezcladas con cebolla, ají, maíz tostado y camote.'),
(26, 3, 'Carapulcra peruana', 1000, 20, 'La carapulcra peruana es un platillo tradicional y emblemático de la gastronomía peruana que ha perdurado a lo largo de los años. Esta deliciosa preparación combina ingredientes autóctonos y técnicas culinarias ancestrales, ofreciendo una experiencia única para el paladar.'),
(27, 4, 'Sandwich de pollo', 999, 2, 'Un sandwich acompañado de tomate y su lechuga.'),
(28, 4, 'Sandwich de palta', 1000, 2, 'Acompañado con su fresca lechuga y su tomate.'),
(29, 4, 'Sandwich de huevo', 1000, 2, 'Ideal para los que gustan acompañada con su jamonada.'),
(30, 4, 'Sandwich de acelga', 1000, 4, 'Rico sandwich acompañado con huevo sancochado y su toque de limon.'),
(31, 4, 'Sandwich de atun', 1000, 2, 'Buen sandwich acompañado de tomate y lechuga, ideal para empezar el dia.'),
(32, 4, 'Sandwich integral', 1000, 2, 'Ideal para consumir en el camino.'),
(33, 4, 'Sandwich gratinado', 1000, 3, 'Un sandwich crocante con su delicioso huevo derretido.'),
(34, 4, 'Sandwich vegano', 1000, 2, 'Acompañado de queso chedar y su lechuga fresca.'),
(35, 5, 'Ceviche', 1000, 0, 'Mariscos frescos marinados en jugo de limón con cebolla y cilantro.'),
(36, 5, 'Papa a la huancaina', 1000, 0, 'Papas en rodajas servidas con una salsa peruana cremosa y picante de queso.'),
(37, 5, 'Sopa del dia', 1000, 0, 'Sopa especial del chef del día con una variedad de ingredientes frescos.'),
(38, 5, 'Ensalada', 1000, 0, 'Ensalada refrescante con hojas verdes mixtas, tomates y aderezo agridulce.'),
(39, 5, 'Adobo de chancho', 1000, 8, 'Cerdo marinado en una sabrosa y picante salsa de adobo, servido con arroz.'),
(40, 5, 'Estofado de pollo', 1000, 7, 'Pollo tierno estofado en un caldo sabroso con verduras.'),
(41, 5, 'Tortilla de raya', 1000, 7, 'Plato peruano tradicional con raya en una tortilla.'),
(42, 5, 'Sudado', 1000, 7, 'Pescado estofado con tomates, cebollas y especias peruanas.'),
(43, 5, 'Aeropuerto', 1000, 9, 'Un plato contundente con una variedad de carnes, papas y arroz.'),
(44, 5, 'Bisteck a lo pobre', 1000, 9, 'Bistec de res servido con arroz, plátanos fritos y un huevo frito.'),
(45, 5, 'Pescado frito', 1000, 8, 'Pescado crujiente frito servido con yuca frita.'),
(46, 6, 'Arroz arabe', 1000, 30, 'Arroz con especias árabes, aromático y delicioso.'),
(47, 6, 'Cuy con papas', 1000, 30, 'Cuy preparado con papas en una deliciosa combinación de sabores.'),
(48, 6, 'Ensalada rusa con pollo', 1000, 30, 'Ensalada rusa acompañada de tiernos trozos de pollo.'),
(49, 6, 'Lasaña napolitana', 1000, 30, 'Lasaña con capas de carne, salsa de tomate y queso fundido.'),
(50, 6, 'Lomo saltado', 1000, 30, 'Trozos de lomo de res salteados con verduras y aderezos peruanos.'),
(51, 6, 'Sopa wantan', 1000, 30, 'Sopa con dumplings de pollo y vegetales, una deliciosa opción.'),
(52, 7, 'Coca-Cola 500ml', 1000, 3, 'Bebida refrescante y deliciosa en botella de 500 ml.'),
(53, 7, 'Coca-Cola Retornable 3L', 1000, 4, 'Bebida refrescante y deliciosa en botella retornable de 3 litros.'),
(54, 7, 'Inca Kola 1L', 1000, 2, 'Bebida gaseosa peruana muy popular en botella de 1 litro.'),
(55, 7, 'Inca Kola Retornable 2L', 1000, 3, 'Bebida gaseosa peruana muy popular en botella retornable de 2 litros.'),
(56, 7, 'Pepsi 750ml', 1000, 2, 'Refresco carbonatado muy conocido en botella de 750 ml.'),
(57, 7, 'Pepsi Retornable 1.5L', 1000, 3, 'Refresco carbonatado muy conocido en botella retornable de 1.5 litros.'),
(58, 7, 'Sprite 1L', 1000, 3, 'Bebida gaseosa refrescante y cítrica en botella de 1 litro.'),
(59, 7, 'Sprite Retornable 2L', 1000, 4, 'Bebida gaseosa refrescante y cítrica en botella retornable de 2 litros.'),
(60, 7, 'Fanta 1.25L', 1000, 3, 'Refresco de naranja con burbujas en botella de 1.25 litros.'),
(61, 7, 'Fanta Retornable 2.5L', 1000, 4, 'Refresco de naranja con burbujas en botella retornable de 2.5 litros.'),
(62, 7, 'Kola Inglesa 600ml', 1000, 2, 'Gaseosa peruana con sabor a cola en botella de 600 ml.'),
(63, 7, 'Guaraná 355ml', 1000, 2, 'Bebida gaseosa peruana con sabor a guaraná en lata de 355 ml.'),
(64, 7, 'Cifrú 750ml', 1000, 2, 'Gaseosa peruana con sabor a frutas en botella de 750 ml.'),
(65, 7, 'Vivaqua 500ml', 1000, 2, 'Gaseosa peruana con sabor a frutas tropicales en botella de 500 ml.'),
(66, 7, 'Triple Kola 330ml', 1000, 2, 'Gaseosa peruana con sabor a cola en lata de 330 ml.'),
(67, 7, 'Cristal 600ml', 1000, 3, 'Gaseosa peruana con sabor a frutas cítricas en botella de 600 ml.'),
(68, 7, 'Coca Cola 355ml', 1000, 2, 'Gaseosa peruana con sabor a cola en lata de 355 ml.'),
(69, 7, 'Inka Kola 2.5L', 1000, 4, 'Bebida gaseosa peruana muy popular en botella de 2.5 litros.'),
(70, 7, 'Vivarium 750ml', 1000, 3, 'Gaseosa peruana con sabor a frutas en botella de 750 ml.'),
(71, 7, 'Kola Real 500ml', 1000, 2, 'Gaseosa peruana con sabor a cola en botella de 500 ml.'),
(72, 7, 'Chicha morada', 998, 0, 'Refrescante bebida, elaborada con maiz morado.');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `recuperarclientes`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `recuperarclientes` (
`cCliID` int(11)
,`cPerID` int(11)
,`cPerNombre` varchar(50)
,`cPerApellidos` varchar(50)
,`cPerEdad` date
,`tPerNumDoc` text
,`cPerGenero` varchar(50)
,`cPerPais` varchar(250)
,`cCliTipoCliente` enum('CLIENTE EN RESTAURANTE','IDENTIFICADO')
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipocomprobante`
--

CREATE TABLE `tipocomprobante` (
  `iTipoComID` int(11) NOT NULL,
  `tTipoComNombre` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipocomprobante`
--

INSERT INTO `tipocomprobante` (`iTipoComID`, `tTipoComNombre`) VALUES
(1, 'Boleta'),
(2, 'Factura');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodocumento`
--

CREATE TABLE `tipodocumento` (
  `iTipoDocID` int(11) NOT NULL,
  `tTipoDocNombre` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipodocumento`
--

INSERT INTO `tipodocumento` (`iTipoDocID`, `tTipoDocNombre`) VALUES
(1, 'DNI'),
(2, 'CARNET DE EXTRANJERIA'),
(3, 'PASAPORTE'),
(4, 'OTROO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajador`
--

CREATE TABLE `trabajador` (
  `cTraID` int(11) NOT NULL,
  `cPerID` int(11) NOT NULL,
  `iCarID` int(11) NOT NULL,
  `fTraSueldo` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `trabajador`
--

INSERT INTO `trabajador` (`cTraID`, `cPerID`, `iCarID`, `fTraSueldo`) VALUES
(1, 21, 1, 1499),
(3, 23, 3, 1500),
(4, 24, 2, 1800),
(5, 25, 3, 1500),
(6, 26, 2, 1500),
(7, 41, 1, 1500),
(8, 42, 3, 1100),
(9, 43, 2, 1500),
(11, 45, 1, 1500),
(12, 46, 1, 1500),
(13, 47, 3, 1500),
(14, 49, 2, 1500),
(15, 50, 2, 1599),
(16, 51, 1, 1500),
(17, 57, 3, 1500);

--
-- Disparadores `trabajador`
--
DELIMITER $$
CREATE TRIGGER `trg_ActualizarNombreCargoUsuario` AFTER UPDATE ON `trabajador` FOR EACH ROW BEGIN
    DECLARE nombre_cargo TEXT;
    SELECT tCarNombre INTO nombre_cargo FROM cargo WHERE iCarID = NEW.iCarID;
    UPDATE usuario SET cUserRol = nombre_cargo
    WHERE cPerID = NEW.cPerID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `cUserID` int(11) NOT NULL,
  `cPerID` int(11) DEFAULT NULL,
  `cUserNUsuario` varchar(250) DEFAULT NULL,
  `cUserClave` varchar(250) DEFAULT NULL,
  `cUsuActivo` tinyint(1) NOT NULL,
  `cUsuHabilitado` tinyint(1) NOT NULL,
  `cUserRol` varchar(25) NOT NULL,
  `cUserFechaC` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`cUserID`, `cPerID`, `cUserNUsuario`, `cUserClave`, `cUsuActivo`, `cUsuHabilitado`, `cUserRol`, `cUserFechaC`) VALUES
(18, 21, 'sanchezporfirio99', '8ed823e06e5481551076e6106c1fed5aa6c7fc8d193190c3e1fd370974754517', 0, 1, 'Administrador', '2024-01-28 17:53:47'),
(19, 23, 'Eduardo Hernandez', '9caa5fb69722f96148e32893b6e4ae4c06028b6a9e9a109b9e3c581f213db27a', 0, 1, 'Mozo', '2024-01-28 17:53:47'),
(20, 24, 'Carlos Larios', 'c345af53ba959ebde17cdae70fbc52f9f0b2de41805d66ac14e2fd9ddb5c8e29', 0, 1, 'Cajero', '2024-01-28 17:53:47'),
(21, 25, 'Javier Bravo', '3f7fd72bf2a3a69010e336849f8daad09e8802295d2b16c0f4b5fe14c9cc78c9', 0, 1, 'Mozo', '2024-01-28 17:53:47'),
(22, 26, 'Percy Bravo', '244f6da1ec118f6f4335c01f974c525e4c1a805f140de608dacaff6c4334b87a', 0, 1, 'Cajero', '2024-01-28 17:53:47'),
(37, 41, 'Jose Manuel Diaz Larios', '47a0478336ae42ae0abd283c3dbe061b05a25e6409c388f8ef8453ddd7bb82b6', 0, 1, 'Administrador', '2024-01-31 19:09:46'),
(38, 42, 'Jose Luis Diaz Polo', '8190318c267f7947fc24c0f3977911e369b4a0775d250f657e71caa1304e02bf', 0, 1, 'Mozo', '2024-02-03 23:32:12'),
(40, 43, 'Junior Cachay', '88757c8192662877f263603f67c028676214b487c1e2ab61b7b9159704412775', 1, 1, 'Cajero', '2024-02-09 17:13:24'),
(44, 45, 'Jose Darwin Santamaria Chaponan', '52b12f1b563fcb7a017a72962718a9a95a49966d0df415c22075ad71edb6fe53', 0, 1, 'Administrador', '2024-02-09 17:49:19'),
(45, 46, 'Jimena Palomino Malca', 'd0e65eb8762a3ca41186a1487e71cbdd5b19f2724b28dab0e86288a1e625bbf2', 0, 1, 'Administrador', '2024-02-10 00:04:46'),
(46, 47, 'Jose Eduardo Medianero Bernal', '3d91893a9af0d1a273b4806ef40e016c440d961b151e7c0ed87915c0f15e8146', 0, 1, 'Mozo', '2024-02-10 00:10:46'),
(47, 49, 'Juan Inga Campos', '2674933cb51907def2319b36144fb3197550c0d41e13e3111eea72050b6785e4', 0, 1, 'Cajero', '2024-05-20 20:01:29'),
(48, 50, 'Edward Smith More Purihuaman', '392715bc0fba9fadfc3a96e5757a28b3bcbaee9667a29ef244c969bfaa3a7e26', 0, 1, 'Cajero', '2024-05-20 20:08:18'),
(49, 51, 'Jhon Franklin Ramos Ortiz', 'd78b34f6fd0ccc1ecffffa6ba516342c29cb6fab83730e7639f859d10cc90bc9', 0, 1, 'Administrador', '2024-05-20 20:09:11'),
(51, 57, 'jhair23', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', 0, 1, 'Mozo', '2024-05-30 18:39:01');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `usuariostrabajadores`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `usuariostrabajadores` (
`cPerID` int(11)
,`cTraID` int(11)
,`cUserID` int(11)
,`cPerNombre` varchar(50)
,`cPerApellidos` varchar(50)
,`iTipoDocID` int(11)
,`tPerNumDoc` text
,`cPerCorreo` varchar(50)
,`cPerGenero` varchar(50)
,`cPerPais` varchar(250)
,`cUserNUsuario` varchar(250)
,`cUserClave` varchar(250)
,`fTraSueldo` float
,`iCarID` int(11)
,`cUserRol` varchar(25)
,`cUsuHabilitado` tinyint(1)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `iVenID` int(11) NOT NULL,
  `cCliID` int(11) DEFAULT NULL,
  `cTraID` int(11) DEFAULT NULL,
  `iTipoComID` int(11) DEFAULT NULL,
  `dPedFecha` datetime DEFAULT current_timestamp(),
  `fPedTotal` decimal(10,1) DEFAULT 0.0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_pedidos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_pedidos` (
`ID_Pedido` int(11)
,`ID_Cliente` int(11)
,`TipoCliente` enum('CLIENTE EN RESTAURANTE','IDENTIFICADO')
,`NombreApellidoCliente` varchar(101)
,`ID_Trabajador_Mozo` int(11)
,`NombreApellidoMozo` varchar(101)
,`Total` int(11)
,`Fecha` datetime
,`Estado` enum('EN_PROCESO_VENTA','ANULADO','PAGADO')
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_productos_vendidos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_productos_vendidos` (
`cPlaNombre` text
,`TotalCantidad` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_trabajadores_cargo`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_trabajadores_cargo` (
`Cargo` text
,`Cantidad_Trabajadores` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `recuperarclientes`
--
DROP TABLE IF EXISTS `recuperarclientes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `recuperarclientes`  AS SELECT `c`.`cCliID` AS `cCliID`, `c`.`cPerID` AS `cPerID`, `p`.`cPerNombre` AS `cPerNombre`, `p`.`cPerApellidos` AS `cPerApellidos`, `p`.`cPerEdad` AS `cPerEdad`, `p`.`tPerNumDoc` AS `tPerNumDoc`, `p`.`cPerGenero` AS `cPerGenero`, `p`.`cPerPais` AS `cPerPais`, `c`.`cCliTipoCliente` AS `cCliTipoCliente` FROM (`cliente` `c` left join `persona` `p` on(`c`.`cPerID` = `p`.`cPerID`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `usuariostrabajadores`
--
DROP TABLE IF EXISTS `usuariostrabajadores`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `usuariostrabajadores`  AS SELECT `p`.`cPerID` AS `cPerID`, `t`.`cTraID` AS `cTraID`, `u`.`cUserID` AS `cUserID`, `p`.`cPerNombre` AS `cPerNombre`, `p`.`cPerApellidos` AS `cPerApellidos`, `p`.`iTipoDocID` AS `iTipoDocID`, `p`.`tPerNumDoc` AS `tPerNumDoc`, `p`.`cPerCorreo` AS `cPerCorreo`, `p`.`cPerGenero` AS `cPerGenero`, `p`.`cPerPais` AS `cPerPais`, `u`.`cUserNUsuario` AS `cUserNUsuario`, `u`.`cUserClave` AS `cUserClave`, `t`.`fTraSueldo` AS `fTraSueldo`, `t`.`iCarID` AS `iCarID`, `u`.`cUserRol` AS `cUserRol`, `u`.`cUsuHabilitado` AS `cUsuHabilitado` FROM ((`persona` `p` join `usuario` `u` on(`u`.`cPerID` = `p`.`cPerID`)) join `trabajador` `t` on(`t`.`cPerID` = `p`.`cPerID`)) WHERE `u`.`cUserRol` <> 'normal' ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_pedidos`
--
DROP TABLE IF EXISTS `vw_pedidos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_pedidos`  AS SELECT `p`.`cPedID` AS `ID_Pedido`, `p`.`cCliID` AS `ID_Cliente`, `c`.`cCliTipoCliente` AS `TipoCliente`, concat(`cli_persona`.`cPerNombre`,' ',`cli_persona`.`cPerApellidos`) AS `NombreApellidoCliente`, `p`.`cTraID` AS `ID_Trabajador_Mozo`, concat(`mozo_persona`.`cPerNombre`,' ',`mozo_persona`.`cPerApellidos`) AS `NombreApellidoMozo`, `p`.`cPedTotal` AS `Total`, `p`.`dPedFecha` AS `Fecha`, `p`.`cPedEstado` AS `Estado` FROM ((((`pedido` `p` left join `cliente` `c` on(`c`.`cCliID` = `p`.`cCliID`)) left join `persona` `cli_persona` on(`cli_persona`.`cPerID` = `c`.`cPerID`)) join `trabajador` `t` on(`t`.`cTraID` = `p`.`cTraID`)) join `persona` `mozo_persona` on(`mozo_persona`.`cPerID` = `t`.`cPerID`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_productos_vendidos`
--
DROP TABLE IF EXISTS `vw_productos_vendidos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_productos_vendidos`  AS SELECT `p`.`cPlaNombre` AS `cPlaNombre`, sum(`dv`.`iDetCantidad`) AS `TotalCantidad` FROM (`detalleventa` `dv` join `platos` `p` on(`dv`.`iPlaID` = `p`.`cPlaID`)) GROUP BY `p`.`cPlaNombre` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_trabajadores_cargo`
--
DROP TABLE IF EXISTS `vw_trabajadores_cargo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_trabajadores_cargo`  AS SELECT `c`.`tCarNombre` AS `Cargo`, count(distinct `t`.`cPerID`) AS `Cantidad_Trabajadores` FROM (`trabajador` `t` join `cargo` `c` on(`t`.`iCarID` = `c`.`iCarID`)) GROUP BY `c`.`tCarNombre` ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`id_caja`);

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`iCarID`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`cCatID`);

--
-- Indices de la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`cCliID`),
  ADD KEY `cliente_ibfk_1` (`cPerID`);

--
-- Indices de la tabla `detallecaja`
--
ALTER TABLE `detallecaja`
  ADD PRIMARY KEY (`iDcID`),
  ADD KEY `iCajaID` (`iCajaID`);

--
-- Indices de la tabla `detallepagos`
--
ALTER TABLE `detallepagos`
  ADD PRIMARY KEY (`cDetPagoID`);

--
-- Indices de la tabla `detallepedido`
--
ALTER TABLE `detallepedido`
  ADD PRIMARY KEY (`iDepID`),
  ADD KEY `detallepedido_ibfk_1` (`cPedID`);

--
-- Indices de la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  ADD PRIMARY KEY (`iDetID`),
  ADD KEY `iPlaID` (`iPlaID`),
  ADD KEY `detalleventa_ibfk_1` (`iVenID`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`cPagoID`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`cPedID`),
  ADD KEY `FK_cTraID_Pedido` (`cTraID`),
  ADD KEY `FK_cCliID_Pedido` (`cCliID`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`cPerID`),
  ADD KEY `iTipoDocID` (`iTipoDocID`);

--
-- Indices de la tabla `platos`
--
ALTER TABLE `platos`
  ADD PRIMARY KEY (`cPlaID`),
  ADD KEY `cCatID` (`cCatID`);

--
-- Indices de la tabla `tipocomprobante`
--
ALTER TABLE `tipocomprobante`
  ADD PRIMARY KEY (`iTipoComID`);

--
-- Indices de la tabla `tipodocumento`
--
ALTER TABLE `tipodocumento`
  ADD PRIMARY KEY (`iTipoDocID`);

--
-- Indices de la tabla `trabajador`
--
ALTER TABLE `trabajador`
  ADD PRIMARY KEY (`cTraID`),
  ADD KEY `cPerID` (`cPerID`),
  ADD KEY `iCarID` (`iCarID`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`cUserID`),
  ADD KEY `cPerID` (`cPerID`);

--
-- Indices de la tabla `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`iVenID`),
  ADD KEY `venta_ibfk_2` (`cTraID`),
  ADD KEY `venta_ibfk_3` (`iTipoComID`),
  ADD KEY `venta_ibfk_1` (`cCliID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `caja`
--
ALTER TABLE `caja`
  MODIFY `id_caja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `iCarID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `cCatID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `cCliID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `detallecaja`
--
ALTER TABLE `detallecaja`
  MODIFY `iDcID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detallepagos`
--
ALTER TABLE `detallepagos`
  MODIFY `cDetPagoID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detallepedido`
--
ALTER TABLE `detallepedido`
  MODIFY `iDepID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  MODIFY `iDetID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `cPagoID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `cPedID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `cPerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT de la tabla `platos`
--
ALTER TABLE `platos`
  MODIFY `cPlaID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT de la tabla `tipocomprobante`
--
ALTER TABLE `tipocomprobante`
  MODIFY `iTipoComID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipodocumento`
--
ALTER TABLE `tipodocumento`
  MODIFY `iTipoDocID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `trabajador`
--
ALTER TABLE `trabajador`
  MODIFY `cTraID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `cUserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `venta`
--
ALTER TABLE `venta`
  MODIFY `iVenID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`cPerID`) REFERENCES `persona` (`cPerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detallecaja`
--
ALTER TABLE `detallecaja`
  ADD CONSTRAINT `detallecaja_ibfk_1` FOREIGN KEY (`iCajaID`) REFERENCES `caja` (`id_caja`);

--
-- Filtros para la tabla `detallepedido`
--
ALTER TABLE `detallepedido`
  ADD CONSTRAINT `detallepedido_ibfk_1` FOREIGN KEY (`cPedID`) REFERENCES `pedido` (`cPedID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  ADD CONSTRAINT `detalleventa_ibfk_1` FOREIGN KEY (`iVenID`) REFERENCES `venta` (`iVenID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detalleventa_ibfk_2` FOREIGN KEY (`iPlaID`) REFERENCES `platos` (`cPlaID`);

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `FK_cCliID_Pedido` FOREIGN KEY (`cCliID`) REFERENCES `cliente` (`cCliID`),
  ADD CONSTRAINT `FK_cTraID_Pedido` FOREIGN KEY (`cTraID`) REFERENCES `trabajador` (`cTraID`);

--
-- Filtros para la tabla `persona`
--
ALTER TABLE `persona`
  ADD CONSTRAINT `persona_ibfk_1` FOREIGN KEY (`iTipoDocID`) REFERENCES `tipodocumento` (`iTipoDocID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `platos`
--
ALTER TABLE `platos`
  ADD CONSTRAINT `platos_ibfk_1` FOREIGN KEY (`cCatID`) REFERENCES `categoria` (`cCatID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `trabajador`
--
ALTER TABLE `trabajador`
  ADD CONSTRAINT `trabajador_ibfk_1` FOREIGN KEY (`cPerID`) REFERENCES `persona` (`cPerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trabajador_ibfk_2` FOREIGN KEY (`iCarID`) REFERENCES `cargo` (`iCarID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`cPerID`) REFERENCES `persona` (`cPerID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`cCliID`) REFERENCES `cliente` (`cCliID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`cTraID`) REFERENCES `trabajador` (`cTraID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `venta_ibfk_3` FOREIGN KEY (`iTipoComID`) REFERENCES `tipocomprobante` (`iTipoComID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
