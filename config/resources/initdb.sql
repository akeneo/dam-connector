CREATE TABLE `synchronize_assets_execution`
(
    `id`          int          NOT NULL AUTO_INCREMENT,
    `family_code` varchar(200) NOT NULL,
    `status`      varchar(50)  NOT NULL,
    `start_time`  datetime     NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = 'InnoDB'
  COLLATE 'utf8mb4_unicode_ci';
