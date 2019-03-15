DROP FUNCTION IF EXISTS `uc_valid_range`;
DELIMITER |
CREATE FUNCTION uc_valid_range(av_start SMALLINT, av_end SMALLINT, ye_until TINYINT)
	RETURNS INT(6)
	READS SQL DATA
	BEGIN
		DECLARE cur_year TINYINT(4);
		DECLARE rstr VARCHAR(10);
		DECLARE doy, day_diff2, year_diff, day_diff SMALLINT(3);

		SET cur_year = SUBSTRING(YEAR(CURDATE()), 2, 4),
			doy = DAYOFYEAR(CURDATE()),
			day_diff2 = (av_end - doy),
			year_diff = (ye_until - cur_year),
			day_diff = (av_end - av_start);
		CASE
			WHEN 
		SET rstr =  CONCAT(IF(year_diff <= 0, '-', ''), IF(day_diff2 > 0, day_diff2, 0));

		RETURN rstr;
	END 