-- Engagement Kit migration 001 (idempotent; prefix eng_ — edit if your config uses another)
CREATE TABLE IF NOT EXISTS eng_users (
    id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    google_sub    CHAR(34)     NOT NULL UNIQUE,           -- Google's stable id
    name          VARCHAR(50)  NOT NULL,                  -- sanitised display name
    email_hash    CHAR(64)     NOT NULL,                  -- sha256(salt+email), moderation lookup only
    created_at    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_sub (google_sub)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS eng_sparks (
    id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id       BIGINT UNSIGNED NOT NULL,
    post_id       VARCHAR(190) NOT NULL,                  -- blog-side post slug/id
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_post (user_id, post_id),           -- one spark per user per post
    KEY idx_post (post_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS eng_comments (
    id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    post_id       VARCHAR(190) NOT NULL,
    user_id       BIGINT UNSIGNED NOT NULL,
    body          TEXT NOT NULL,                          -- sanitised+masked on submit
    status        ENUM('pending','approved','deleted') NOT NULL DEFAULT 'pending',
    masked        TINYINT(1) NOT NULL DEFAULT 0,          -- informational: masks were applied
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    moderated_at  DATETIME NULL,
    KEY idx_post_status (post_id, status, created_at),
    KEY idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS eng_rate_limits (
    bucket        VARCHAR(64)  NOT NULL,                  -- e.g. "comment:u123", "comment:ip:1.2.3.4"
    window_start  DATETIME     NOT NULL,
    count         INT          NOT NULL DEFAULT 0,
    PRIMARY KEY (bucket, window_start)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS eng_moderation_log (
    id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    action        VARCHAR(32)  NOT NULL,                 -- approve|delete|login
    target_type   VARCHAR(16)  NOT NULL,                 -- comment
    target_id     BIGINT UNSIGNED NOT NULL,
    detail        VARCHAR(255) NULL,
    created_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_target (target_type, target_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;