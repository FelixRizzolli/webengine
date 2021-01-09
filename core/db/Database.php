<?php

namespace app\core\db;

use app\core\Application;
use PDO;
use PDOStatement;

/**
 * Class Database
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 * @package app\core
 */
class Database {
    private PDO $pdo;

    public function __construct(array $config) {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';

        $this->setPDO(new PDO($dsn, $user, $password));
        $this->getPDO()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function setPDO(PDO $pdo): void {
        $this->pdo = $pdo;
    }
    public function getPDO(): PDO {
        return $this->pdo;
    }

    public function applyMigrations(): void {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $files = scandir(Application::$ROOT_DIR . '/migrations');
        $toApplyMigrations = array_diff($files, $appliedMigrations);

        foreach ($toApplyMigrations as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }

            require_once(Application::$ROOT_DIR . '/migrations/' . $migration);
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();

            $this->log("Applying migration {$migration}" . PHP_EOL);
            $instance->up();
            $this->log("Applied migration {$migration}" . PHP_EOL);

            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations)){
            $this->saveMigrations($newMigrations);
        } else {
            $this->log("All migrations are applied");
        }
    }
    public function createMigrationsTable() {
        $sql = ("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
            ) ENGINE=INNODB
        ;");
        $this->getPDO()->exec($sql);
    }
    public function getAppliedMigrations(): array {
        $statement = $this->getPDO()->prepare("
            SELECT migration
            FROM migrations
        ;");
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }
    public function saveMigrations(array $migrations): void {
        $values = implode(",", array_map(fn($m) => "('$m')", $migrations));

        $sql = ("
            INSERT INTO migrations (
                migration
            ) 
            VALUES {$values}
        ;");
        $statement = $this->getPDO()->prepare($sql);
        $statement->execute();
    }

    protected function log($message): void {
        echo('[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL);
    }

    public function prepareQuery($sql): PDOStatement {
        return $this->getPDO()->prepare($sql);
    }
}