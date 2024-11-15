-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 15-11-2024 a las 08:06:57
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
CREATE DATABASE IF NOT EXISTS `d_lola` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `d_lola`;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `iCarID` int(11) NOT NULL,
  `tCarNombre` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `cCatID` int(11) NOT NULL,
  `cCatImagen` longblob NOT NULL,
  `cCatNombre` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

--
-- Disparadores `detalleventa`
--
DELIMITER $$
CREATE TRIGGER `trg_DisminuirCantidadProducto` AFTER INSERT ON `detalleventa` FOR EACH ROW BEGIN
    UPDATE platos
    SET cPlaCantidad = cPlaCantidad - NEW.iDetCantidad
    WHERE cPlaID = NEW.iPlaID;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesa`
--

CREATE TABLE `mesa` (
  `id_mesa` int(11) NOT NULL,
  `capacidad` int(11) DEFAULT NULL,
  `estado` enum('LIBRE','OCUPADA') DEFAULT 'LIBRE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `cPagoID` int(11) NOT NULL,
  `cPagoTipo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `cPedID` int(11) NOT NULL,
  `cMesID` int(11) NOT NULL,
  `cCliID` int(11) DEFAULT NULL,
  `cTraID` int(11) DEFAULT NULL,
  `cPedTotal` int(11) NOT NULL DEFAULT 0,
  `dPedFecha` datetime DEFAULT current_timestamp(),
  `cPedEstado` enum('EN_PROCESO_VENTA','ANULADO','PAGADO','PARA_CORREGIR','PENDIENTE') NOT NULL DEFAULT 'PENDIENTE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `pedido`
--
DELIMITER $$
CREATE TRIGGER `DisminuirCapacidadMesa` AFTER INSERT ON `pedido` FOR EACH ROW BEGIN 
    DECLARE capacidad_actual INT;
    UPDATE mesa
    SET capacidad = capacidad - 1
    WHERE id_mesa = NEW.cMesID;
    
    SELECT capacidad INTO capacidad_actual 
    FROM mesa 
    WHERE id_mesa = NEW.cMesID;
    
    IF capacidad_actual = 0 THEN
        UPDATE mesa
        SET estado = 'OCUPADA'
        WHERE id_mesa = NEW.cMesID;
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `RestaurarCapacidadMesa` AFTER DELETE ON `pedido` FOR EACH ROW BEGIN
    DECLARE capacidad_actual INT;
    SELECT capacidad INTO capacidad_actual FROM mesa WHERE id_mesa = OLD.cMesID;
    
    IF capacidad_actual = 0 THEN
        UPDATE mesa SET estado = 'LIBRE' WHERE id_mesa = OLD.cMesID;
    END IF;
    
    UPDATE mesa SET capacidad = capacidad + 1 WHERE id_mesa = OLD.cMesID;
END
$$
DELIMITER ;
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
  `cPerDireccion` varchar(250) DEFAULT NULL,
  `cPerCorreo` varchar(50) DEFAULT NULL,
  `cPerGenero` varchar(50) DEFAULT NULL,
  `cPerPais` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `cPlaImagen` longblob NOT NULL,
  `cPlaNombre` text NOT NULL,
  `cPlaCantidad` int(11) NOT NULL,
  `cPlaPrecio` decimal(10,0) NOT NULL,
  `cPlaDescripcion` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipodocumento`
--

CREATE TABLE `tipodocumento` (
  `iTipoDocID` int(11) NOT NULL,
  `tTipoDocNombre` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
,`cUsuActivo` tinyint(1)
,`cUsuHabilitado` tinyint(1)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

CREATE TABLE `venta` (
  `iVenID` int(11) NOT NULL,
  `cMesID` int(11) NOT NULL,
  `cCliID` int(11) DEFAULT NULL,
  `cTraID` int(11) DEFAULT NULL,
  `iTipoComID` int(11) DEFAULT NULL,
  `dVenFecha` datetime DEFAULT current_timestamp(),
  `fVenTotal` decimal(10,1) DEFAULT 0.0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_pedidos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_pedidos` (
`ID_Pedido` int(11)
,`ID_Cliente` int(11)
,`cMesID` int(11)
,`TipoCliente` enum('CLIENTE EN RESTAURANTE','IDENTIFICADO')
,`NombreApellidoCliente` varchar(101)
,`ID_Trabajador_Mozo` int(11)
,`NombreApellidoMozo` varchar(101)
,`Total` int(11)
,`Fecha` datetime
,`Estado` enum('EN_PROCESO_VENTA','ANULADO','PAGADO','PARA_CORREGIR','PENDIENTE')
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
-- Estructura Stand-in para la vista `vw_ventas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_ventas` (
`ID_VENTA` int(11)
,`ID_CLIENTE` int(11)
,`TIPOCLIENTE` enum('CLIENTE EN RESTAURANTE','IDENTIFICADO')
,`NombreApellidoCliente` varchar(101)
,`ID_TRABAJADOR_CAJERO` int(11)
,`NOMBRE_APELLIDO_CAJERO` varchar(101)
,`ID_TIPO_COMPROBANTE` int(11)
,`COMPROBANTE` text
,`Total` decimal(10,1)
,`Fecha` datetime
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

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `usuariostrabajadores`  AS SELECT `p`.`cPerID` AS `cPerID`, `t`.`cTraID` AS `cTraID`, `u`.`cUserID` AS `cUserID`, `p`.`cPerNombre` AS `cPerNombre`, `p`.`cPerApellidos` AS `cPerApellidos`, `p`.`iTipoDocID` AS `iTipoDocID`, `p`.`tPerNumDoc` AS `tPerNumDoc`, `p`.`cPerCorreo` AS `cPerCorreo`, `p`.`cPerGenero` AS `cPerGenero`, `p`.`cPerPais` AS `cPerPais`, `u`.`cUserNUsuario` AS `cUserNUsuario`, `u`.`cUserClave` AS `cUserClave`, `t`.`fTraSueldo` AS `fTraSueldo`, `t`.`iCarID` AS `iCarID`, `u`.`cUserRol` AS `cUserRol`, `u`.`cUsuActivo` AS `cUsuActivo`, `u`.`cUsuHabilitado` AS `cUsuHabilitado` FROM ((`persona` `p` join `usuario` `u` on(`u`.`cPerID` = `p`.`cPerID`)) join `trabajador` `t` on(`t`.`cPerID` = `p`.`cPerID`)) WHERE `u`.`cUserRol` <> 'normal' ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_pedidos`
--
DROP TABLE IF EXISTS `vw_pedidos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_pedidos`  AS SELECT `p`.`cPedID` AS `ID_Pedido`, `p`.`cCliID` AS `ID_Cliente`, `p`.`cMesID` AS `cMesID`, `c`.`cCliTipoCliente` AS `TipoCliente`, concat(`cli_persona`.`cPerNombre`,' ',`cli_persona`.`cPerApellidos`) AS `NombreApellidoCliente`, `p`.`cTraID` AS `ID_Trabajador_Mozo`, concat(`mozo_persona`.`cPerNombre`,' ',`mozo_persona`.`cPerApellidos`) AS `NombreApellidoMozo`, `p`.`cPedTotal` AS `Total`, `p`.`dPedFecha` AS `Fecha`, `p`.`cPedEstado` AS `Estado` FROM ((((`pedido` `p` left join `cliente` `c` on(`c`.`cCliID` = `p`.`cCliID`)) left join `persona` `cli_persona` on(`cli_persona`.`cPerID` = `c`.`cPerID`)) join `trabajador` `t` on(`t`.`cTraID` = `p`.`cTraID`)) join `persona` `mozo_persona` on(`mozo_persona`.`cPerID` = `t`.`cPerID`)) ;

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

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_ventas`
--
DROP TABLE IF EXISTS `vw_ventas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_ventas`  AS SELECT `v`.`iVenID` AS `ID_VENTA`, `v`.`cCliID` AS `ID_CLIENTE`, `c`.`cCliTipoCliente` AS `TIPOCLIENTE`, concat(`cli_persona`.`cPerNombre`,' ',`cli_persona`.`cPerApellidos`) AS `NombreApellidoCliente`, `v`.`cTraID` AS `ID_TRABAJADOR_CAJERO`, concat(`cajero_persona`.`cPerNombre`,' ',`cajero_persona`.`cPerApellidos`) AS `NOMBRE_APELLIDO_CAJERO`, `v`.`iTipoComID` AS `ID_TIPO_COMPROBANTE`, `comprobante`.`tTipoComNombre` AS `COMPROBANTE`, `v`.`fVenTotal` AS `Total`, `v`.`dVenFecha` AS `Fecha` FROM (((((`venta` `v` left join `cliente` `c` on(`c`.`cCliID` = `v`.`cCliID`)) left join `persona` `cli_persona` on(`cli_persona`.`cPerID` = `c`.`cPerID`)) join `trabajador` `t` on(`t`.`cTraID` = `v`.`cTraID`)) join `persona` `cajero_persona` on(`cajero_persona`.`cPerID` = `t`.`cPerID`)) join `tipocomprobante` `comprobante` on(`comprobante`.`iTipoComID` = `v`.`iTipoComID`)) ;

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
  ADD PRIMARY KEY (`cDetPagoID`),
  ADD KEY `detallepagos_ibfk_1` (`iVenID`),
  ADD KEY `detallepagos_ibfk_2` (`cPagoID`);

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
-- Indices de la tabla `mesa`
--
ALTER TABLE `mesa`
  ADD PRIMARY KEY (`id_mesa`);

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
  ADD KEY `FK_cCliID_Pedido` (`cCliID`),
  ADD KEY `FK_cMesID_Pedido` (`cMesID`);

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
  ADD KEY `venta_ibfk_1` (`cCliID`),
  ADD KEY `cMesID` (`cMesID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `caja`
--
ALTER TABLE `caja`
  MODIFY `id_caja` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `iCarID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `cCatID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `cliente`
--
ALTER TABLE `cliente`
  MODIFY `cCliID` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `iDepID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalleventa`
--
ALTER TABLE `detalleventa`
  MODIFY `iDetID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `mesa`
--
ALTER TABLE `mesa`
  MODIFY `id_mesa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `cPagoID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `cPedID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `cPerID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `platos`
--
ALTER TABLE `platos`
  MODIFY `cPlaID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipocomprobante`
--
ALTER TABLE `tipocomprobante`
  MODIFY `iTipoComID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipodocumento`
--
ALTER TABLE `tipodocumento`
  MODIFY `iTipoDocID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `trabajador`
--
ALTER TABLE `trabajador`
  MODIFY `cTraID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `cUserID` int(11) NOT NULL AUTO_INCREMENT;

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
-- Filtros para la tabla `detallepagos`
--
ALTER TABLE `detallepagos`
  ADD CONSTRAINT `detallepagos_ibfk_1` FOREIGN KEY (`iVenID`) REFERENCES `venta` (`iVenID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detallepagos_ibfk_2` FOREIGN KEY (`cPagoID`) REFERENCES `pago` (`cPagoID`);

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
  ADD CONSTRAINT `FK_cMesID_Pedido` FOREIGN KEY (`cMesID`) REFERENCES `mesa` (`id_mesa`),
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
  ADD CONSTRAINT `venta_ibfk_3` FOREIGN KEY (`iTipoComID`) REFERENCES `tipocomprobante` (`iTipoComID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `venta_ibfk_4` FOREIGN KEY (`cMesID`) REFERENCES `mesa` (`id_mesa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
