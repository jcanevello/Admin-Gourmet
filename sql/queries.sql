
USE `gourmet`;

-- ALTER TABLE  `reserva` ADD  `estado` INT NOT NULL COMMENT  '0: pendiente, 1:reservado' AFTER  `telefono` ;

ALTER TABLE  `reserva` CHANGE  `idreserva`  `idreserva` VARCHAR( 30 ) NOT NULL ;
