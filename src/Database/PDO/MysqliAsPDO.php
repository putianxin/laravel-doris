<?php

namespace Ptx\LaravelDoris\Database\PDO;

use \PDO;
use Ptx\LaravelDoris\Database\PDOTrait\PDOTrait;

/**
 * @deprecated
 */
class MysqliAsPDO extends PDO
{
    use PDOTrait;

    /**
     * @var \mysqli
     */
    protected $mysqli = null;

    /**
     * 执行语句 返回影响行数
     */
    public function exec(string $statement): int|false
    {
        // parent::exec();
        $stmt = $this->prepare($statement);
        if ($stmt->execute() === false) {
            return false;
        }

        return $stmt->rowCount();
    }

    /**
     * 获取预处理对象
     */
    public function prepare(string $query, array $options = []): MysqliStmtAsPDOStatement|false
    {
        return new MysqliStmtAsPDOStatement($this->mysqli, $query, $options);
    }

    /**
     * doris 数据库没有lastInsertId
     * @return string|false // 0
     */
    public function lastInsertId(?string $name = null): string|false
    {
        // parent::lastInsertId();
        return $this->mysqli->insert_id;
    }
}
