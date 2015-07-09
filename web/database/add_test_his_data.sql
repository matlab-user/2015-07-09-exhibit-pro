
USE hx_k_db;
DROP PROCEDURE IF EXISTS add_his_data;

DELIMITER |
CREATE PROCEDURE add_his_data( IN g1 CHAR(32) CHARACTER SET utf8 )
	MAIN:BEGIN
		SET @i=0;
		SET @ct = unix_timestamp();
		SELECT @ct;
		WHILE @i<700 DO
			CALL hx_k_db.add_data( g1, rand(), 'p', @ct, 0 );
			CALL hx_k_db.add_data( g1, rand(), 'f', @ct, 0 );
			CALL hx_k_db.add_data( g1, rand(), 'r', @ct, 0 );
			CALL hx_k_db.add_data( g1, rand(), 't', @ct, 0 );
			SET @ct = @ct+10;
			SET @i = @i+1;
		END WHILE;
	END MAIN
|
DELIMITER ;

CALL hx_k_db.add_his_data( 's001' );