<?php

/**
 * Class m0001_initial
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 */
class m0001_initial {

    public function up(): void {
        $database = \app\core\Application::$app->getDatabase();
        $sql = ("
            CREATE TABLE user (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) NOT NULL,
                firstname VARCHAR(255) NOT NULL,
                lastname VARCHAR(255) NOT NULL,
                password VARCHAR (512) NOT NULL,
                status TINYINT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB
        ;");
        $database->getPDO()->exec($sql);
    }

    public function down(): void {
        $database = \app\core\Application::$app->getDatabase();
        $sql = ("
            DROP TABLE user
        ;");
        $database->getPDO()->exec($sql);
    }
}