CREATE DATABASE IF NOT EXISTS `sp20150709_db` DEFAULT CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS sp20150709_db.user_t (
	`id` BIGINT NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(60) DEFAULT 'Anonymous' UNIQUE,
	`passwd` VARCHAR(32) NOT NULL DEFAULT 'NULL',
	`type` ENUM('admin','normal') NOT NULL DEFAULT 'normal',
	`valid` BOOLEAN DEFAULT 1,
	PRIMARY KEY ( `id` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS sp20150709_db.dev_t (
	`gid` CHAR(32) UNIQUE DEFAULT '',
	`name` VARCHAR(32) DEFAULT '我的鱼缸',
	`intv` INT DEFAULT 60,	
	`d_ip` VARCHAR(65) DEFAULT '',
	`d_port` INT DEFAULT -1,
	`l_ip` VARCHAR(65) DEFAULT '',
	`l_port` INT DEFAULT -1,
	`valid` BOOLEAN DEFAULT 1,
	PRIMARY KEY ( `gid` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS sp20150709_db.data_t (
	`id` BIGINT NOT NULL AUTO_INCREMENT,
	`dev_id` CHAR(32) NOT NULL,
	`value` DOUBLE DEFAULT 0,
	`v_name` VARCHAR(50) DEFAULT '',
	`time` BIGINT NOT NULL,
	`batch` BIGINT DEFAULT 0,
	PRIMARY KEY ( `id` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS sp20150709_db.normal_t (
	`gid` CHAR(32) DEFAULT '',
	`v_name` VARCHAR(32) DEFAULT '',	
	`valid` BOOLEAN DEFAULT 1,
	`th1` DOUBLE DEFAULT 0,
	`th2` DOUBLE DEFAULT 0,
	PRIMARY KEY ( `gid`, `v_name` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO sp20150709_db.user_t ( id, name, passwd, type ) VALUES ( 1, 'admin', MD5('adminadmin'), 'admin' );

/*-------------------------------------------------------------------------------------------------------------------
		定义存储过程
---------------------------------------------------------------------------------------------------------------------*/

USE sp20150709_db;
DROP PROCEDURE IF EXISTS add_data;

/*
	p - 压力
	t - 温度
	f - 流量
	r - 阻力
*/

DELIMITER |
CREATE PROCEDURE add_data( IN in_gid VARCHAR(8), IN d DOUBLE, IN n CHAR(1), IN utc BIGINT, IN batch BIGINT )
LOOP1:BEGIN

	SET @v = 0;
	SELECT valid INTO @v FROM sp20150709_db.dev_t WHERE gid=in_gid;
	IF FOUND_ROWS()<=0 THEN
		LEAVE LOOP1;
	END IF;
	
	IF @v=0 THEN
		LEAVE LOOP1;
	END IF;
	
	IF n='t' THEN
		INSERT INTO sp20150709_db.data_t ( dev_id, v_name, value, time, batch ) VALUES ( in_gid, n, d, utc, batch );
	END IF;
	
END LOOP1
|
DELIMITER ;
