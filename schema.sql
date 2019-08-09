CREATE SCHEMA `buy_it_again` DEFAULT CHARACTER SET utf8;

CREATE TABLE `buy_it_again`.`product`
(
    `id`    INT         NOT NULL AUTO_INCREMENT,
    `name`  VARCHAR(45) NULL,
    `price` DOUBLE      NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `id_UNIQUE` (`id` ASC)
)
    ENGINE = InnoDB
    DEFAULT CHARACTER SET = utf8;