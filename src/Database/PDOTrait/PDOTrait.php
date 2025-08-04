<?php

namespace Ptx\LaravelDoris\Database\PDOTrait;

use PDO;

trait PDOTrait
{
    /**
     * @param string $dsn // 为config 因为mysqli没有dsn连接方式
     * @param string $username
     * @param string $password
     * @param array $options
     */
    public function __construct($dsn, $username, $password, $options = array())
    {
        $this->mysqli = mysqli_init();
        $config = json_decode($dsn, true);
        $dsn = $config["dsn"];
        // 设置
        $this->buildOptions($options);

        // initiate the connection to the server, using both previously specified timeouts
        $this->mysqli->real_connect(
            $config['host'],
            $username ?? null,
            $password ?? null,
            $config['database'],
            $config['port'],
        );

        parent::__construct($dsn, $username, $password, $options);
    }

    public function disconnect()
    {
        return $this->mysqli->close();
    }

    /**
     * @param array $options
     */
    private function buildOptions($options)
    {
        // specify the connection timeout
        $timeout = $options[PDO::ATTR_TIMEOUT] ?? 30;
        $this->mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, $timeout);

        if (isset($options[PDO::ATTR_EMULATE_PREPARES])) {
            // 是否转int为字符串 跟配置取反
            $flag = !$options[PDO::ATTR_EMULATE_PREPARES];
            $this->mysqli->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, $flag);
        }
    }

    /**
     * @return bool
     * @throws PDOException
     */
    public function beginTransaction(): bool
    {
        $res = $this->prepare('BEGIN;')->execute();
        return $res === false ? false : true;
    }

    /**
     * @return bool
     * @throws PDOException
     */
    public function commit(): bool
    {
        $res = $this->prepare('COMMIT;')->execute();
        return $res === false ? false : true;
    }

    /**
     * @return bool
     * @throws PDOException
     */
    public function rollBack(): bool
    {
        $res = $this->prepare('ROLLBACK;')->execute();
        return $res === false ? false : true;
    }
}