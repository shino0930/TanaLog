-- データベースを作成
CREATE DATABASE mybooks_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- 作成したデータベースを使用
USE mybooks_db;

-- users テーブルを作成
CREATE TABLE `users` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(20) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- books テーブルを作成
CREATE TABLE `books` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `user_id` INT(11) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `author` VARCHAR(255) NULL DEFAULT NULL,
    `publisher` VARCHAR(255) NULL DEFAULT NULL,
    `isbn` VARCHAR(13) NULL DEFAULT NULL,
    `cover_url` VARCHAR(255) NULL DEFAULT NULL,
    `status` ENUM('read', 'unread') NULL DEFAULT 'unread',
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
