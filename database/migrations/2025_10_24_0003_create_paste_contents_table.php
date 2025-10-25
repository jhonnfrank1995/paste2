<?php

return "
CREATE TABLE IF NOT EXISTS `paste_contents` (
  `paste_id` VARCHAR(16) NOT NULL,
  `content` LONGTEXT NOT NULL,
  `encryption_meta` JSON NULL DEFAULT NULL,
  PRIMARY KEY (`paste_id`),
  CONSTRAINT `paste_contents_paste_id_foreign`
    FOREIGN KEY (`paste_id`)
    REFERENCES `pastes` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Añadir el índice FULLTEXT para las búsquedas.
-- Se hace en una sentencia separada porque no todos los motores lo soportan en el CREATE TABLE.
ALTER TABLE `paste_contents` ADD FULLTEXT INDEX `content_fulltext` (`content`);
ALTER TABLE `pastes` ADD FULLTEXT INDEX `title_fulltext` (`title`);
";