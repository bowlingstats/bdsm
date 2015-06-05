 ALTER TABLE `pinfall` CHANGE `frame` `rack` SMALLINT( 2 ) NOT NULL DEFAULT '0';
 
 ALTER TABLE `games` ADD `track_pins` TINYINT( 1 ) NOT NULL DEFAULT '0' AFTER `player_id` ;
 
 ALTER TABLE `pinfall`
  DROP `b1`,
  DROP `b2`,
  DROP `b3`;
 
 ALTER TABLE `pinfall` ADD `pin1` TINYINT( 4 ) NULL ,
ADD `pin2` TINYINT( 4 ) NULL ,
ADD `pin3` TINYINT( 4 ) NULL ,
ADD `pin4` TINYINT( 4 ) NULL ,
ADD `pin5` TINYINT( 4 ) NULL ,
ADD `pin6` TINYINT( 4 ) NULL ,
ADD `pin7` TINYINT( 4 ) NULL ,
ADD `pin8` TINYINT( 4 ) NULL ,
ADD `pin9` TINYINT( 4 ) NULL ,
ADD `pin10` TINYINT( 4 ) NULL ;


