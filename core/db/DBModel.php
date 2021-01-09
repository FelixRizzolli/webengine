<?php


namespace app\core\db;


use app\core\Application;
use app\core\Model;
use PDOStatement;

/**
 * Class DBModel
 *
 * @author Felix Rizzolli <felix.rizzolli@outlook.de>
 * @package app\core
 */
abstract class DBModel extends Model {
    abstract public static function getTableName(): string;
    abstract public static function getAttributes(): array;
    abstract public static function getPrimaryKey(): string;

    public function save(): bool {
        $tableName = $this->getTableName();
        $attributes = implode(',', $this->getAttributes());
        $params = array_map(fn($attribute) => ":{$attribute}", $this->getAttributes());
        $params = implode(',', $params);

        $statement = self::prepareQuery("
            INSERT INTO {$tableName} ({$attributes}) VALUES ({$params})
        ;");

        foreach ($this->getAttributes() as $attribute) {
            $statement->bindValue(":{$attribute}", $this->{$attribute});
        }

        $statement->execute();
        return true;
    }

    public static function prepareQuery($sql): PDOStatement{
        return Application::$app->getDatabase()->getPDO()->prepare($sql);
    }
}