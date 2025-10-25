<?php

return "
CREATE TABLE IF NOT EXISTS `comments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `paste_id` VARCHAR(16) NOT NULL,
  `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `body` TEXT NOT NULL,
  `status` ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `ip_hash` VARCHAR(64) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `comments_paste_id_foreign` (`paste_id` ASC),
  INDEX `comments_user_id_foreign` (`user_id` ASC),
  CONSTRAINT `comments_paste_id_foreign`
    FOREIGN KEY (`paste_id`)
    REFERENCES `pastes` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `comments_user_id_foreign`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";