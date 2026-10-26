CREATE DATABASE IF NOT EXISTS location_link_demo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE location_link_demo;

CREATE TABLE IF NOT EXISTS tracking_links (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  token CHAR(32) NOT NULL UNIQUE,
  label VARCHAR(120) NOT NULL,
  consent_notice VARCHAR(255) NOT NULL DEFAULT 'This page can share your device location only if you choose Allow.',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  click_count INT UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS location_reports (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  link_id BIGINT UNSIGNED NOT NULL,
  latitude DECIMAL(10,7) NOT NULL,
  longitude DECIMAL(10,7) NOT NULL,
  accuracy_meters DECIMAL(10,2) NULL,
  reported_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_report_link FOREIGN KEY (link_id) REFERENCES tracking_links(id) ON DELETE CASCADE,
  INDEX idx_report_link_time (link_id, reported_at)
) ENGINE=InnoDB;
