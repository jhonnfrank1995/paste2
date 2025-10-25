<?php

return "
CREATE TABLE IF NOT EXISTS `settings` (
  `key` VARCHAR(255) NOT NULL,
  `value` TEXT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";