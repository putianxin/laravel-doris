<?php

namespace Ptx\LaravelDoris\Database\Connectors;

use Illuminate\Database\Connectors\MySqlConnector;
use PDO;

class DorisConnector extends MySqlConnector
{
    /**
     * Establish a database connection.
     *
     * @param  array  $config
     * @return \PDO
     */
    public function connect($config)
    {
        $dsn = $this->getDsn($config);
        $options = $this->getOptions($config);

        // We need to grab the PDO options that should be used while making the brand
        // new connection instance. The PDO options control various aspects of the
        // connection's behavior, and some might be specified by the developers.
        // 这里mysqli dsn用config
        $connection = $this->createConnection($dsn, $config, $options);

        if (!empty($config['database'])) {
            $connection->exec("use `{$config['database']}`;");
        }

        $this->configureIsolationLevel($connection, $config);

        $this->configureEncoding($connection, $config);

        // Next, we will check to see if a timezone has been specified in this config
        // and if it has we will issue a statement to modify the timezone with the
        // database. Setting this DB timezone is an optional configuration item.
        $this->configureTimezone($connection, $config);

        $this->setModes($connection, $config);


        return $connection;
    }

    /**
     * Create a new PDO connection instance.
     *
     * @param  string  $dsn
     * @param  string  $username
     * @param  string  $password
     * @param  array  $options
     * @return \PDO
     */
    protected function createPdoConnection($dsn, $username, $password, $options)
    {
        // 判断php版本8
        if (strpos(PHP_VERSION, '8') === 0) {
            return new \Ptx\LaravelDoris\Database\PDO\MysqliAsPDO($dsn, $username, $password, $options);
        } else {
            return new \Ptx\LaravelDoris\Database\PDO74\MysqliAsPDO($dsn, $username, $password, $options);
        }
    }

    /**
     * Get the query to enable strict mode.
     *
     * @param  \PDO  $connection
     * @param  array  $config
     * @return string
     */
    protected function strictMode($connection, $config)
    {
        return "set session sql_mode='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'";
    }
}
