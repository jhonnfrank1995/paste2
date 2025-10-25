<?php

return "
CREATE TABLE IF NOT EXISTS `tags` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `tags_name_unique` (`name` ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `paste_tag` (
  `paste_id` VARCHAR(16) NOT NULL,
  `tag_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`paste_id`, `tag_id`),
  INDEX `paste_tag_tag_id_foreign` (`tag_id` ASC),
  CONSTRAINT `paste_tag_paste_id_foreign`
    FOREIGN KEY (`paste_id`)
    REFERENCES `pastes` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `paste_tag_tag_id_foreign`
    FOREIGN KEY (`tag_id`)
    REFERENCES `tags` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";