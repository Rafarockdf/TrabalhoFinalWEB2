DROP TRIGGER trigger_atualizar_imc_insert;

DELIMITER $$

CREATE TRIGGER trigger_atualizar_imc_insert 
BEFORE INSERT ON usuarios 
FOR EACH ROW 
BEGIN 
    SET NEW.IMC = NEW.peso / (NEW.altura * NEW.altura); 
END;

DELIMITER ;