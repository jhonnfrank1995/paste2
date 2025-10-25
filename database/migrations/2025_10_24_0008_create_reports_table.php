<?php

return "
CREATE TABLE IF NOT EXISTS `reports` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `paste_id` VARCHAR(16) NOT NULL,
  `reporter_ip_hash` VARCHAR(64) NOT NULL,
  `reason` ENUM('spam','phishing','malware','copyright','other') NOT NULL,
  `note` TEXT NULL,
  `status` ENUM('open','resolved') NOT NULL DEFAULT 'open',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `reports_paste_id_foreign` (`paste_id` ASC),
  INDEX `reports_status_index` (`status` ASC),
  CONSTRAINT `reports_paste_id_foreign`
    FOREIGN KEY (`paste_id`)
    REFERENCES `pastes` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
";