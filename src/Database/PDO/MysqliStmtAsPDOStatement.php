<?php

namespace Ptx\LaravelDoris\Database\PDO;

use \PDOStatement;
use Ptx\LaravelDoris\Database\PDOTrait\PDOStatementTrait;

/**
 * @deprecated
 */
class MysqliStmtAsPDOStatement extends PDOStatement
{
    use PDOStatementTrait;

    public function fetchAll(int $mode = \PDO::FETCH_DEFAULT, ...$args): array
    {
        if(!$this->queryResult){
            return [];
        }
        // return parent::fetchAll($mode, ...$args);
        $result = [];
        if ($this->fetchMode === \PDO::FETCH_OBJ) {
            while ($row = $this->queryResult->fetch_object()) {
                $result[] = $row;
            }
        } else {
            $result = $this->queryResult->fetch_all(MYSQLI_ASSOC);
        }

        return $result;
    }

    /**
     * 获取单行数据
     * @param int $mode
     * @param int $cursorOrientation
     * @param int $cursorOffset
     * @return mixed
     */
    public function fetch(
        int $mode = \PDO::FETCH_DEFAULT,
        int $cursorOrientation = \PDO::FETCH_ORI_NEXT,
        int $cursorOffset = 0
    ): mixed {
        if(!$this->queryResult){
            return [];
        }
        // return parent::fetch();
        if ($this->fetchMode === \PDO::FETCH_OBJ) {
            return $this->queryResult->fetch_object();
        }

        return $this->queryResult->fetch_assoc();
    }
}
