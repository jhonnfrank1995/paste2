<?php

return "
CREATE TABLE IF NOT EXISTS `pastes` (
  `id` VARCHAR(16) NOT NULL,
  `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
  `title` VARCHAR(255) NULL DEFAULT NULL,
  `slug` VARCHAR(255) NULL DEFAULT NULL,
  `language` VARCHAR(50) NOT NULL DEFAULT 'plaintext',
  `visibility` ENUM('public','unlisted','private') NOT NULL DEFAULT 'public',
  `has_password` TINYINT(1) NOT NULL DEFAULT 0,
  `password_hash` VARCHAR(255) NULL DEFAULT NULL,
  `encryption` ENUM('none','server','client') NOT NULL DEFAULT 'none',
  `expires_at` TIMESTAMP NULL DEFAULT NULL,
  `burn_after_read` TINYINT(1) NOT NULL DEFAULT 0,
  `size_bytes` INT UNSIGNED NOT NULL,
  `views_count` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `downloads_count` BIGINT UNSIGNED NOT NULL DEFAULT 0,
  `edit_token` VARCHAR(64) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `pastes_slug_unique` (`slug` ASC),
  INDEX `pastes_user_id_foreign` (`user_id` ASC),
  INDEX `pastes_visibility_index` (`visibility` ASC),
  INDEX `pastes_expires_at_index` (`expires_at` ASC),
  CONSTRAINT `pastes_user_id_foreign`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";