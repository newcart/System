<?php

namespace Newcart\System\Modification\System\Library;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

class DB
{

    /**
     * @var Capsule
     */
    private $capsule;

    /**
     * Connection name
     * @var string
     */
    private $name = 'default';

    /**
     * @var \Illuminate\Database\Connection
     */
    private $connection;

    /**
     * @var int
     */
    private $countAffected = 0;

    public function __construct($driver, $hostname, $username, $password, $database, $port = NULL, $prefix = NULL, $name = 'default')
    {
        $this->name = $name;

        if (!in_array($driver, ['mysql', 'pgsql', 'sqlite', 'sqlsrv'])) {
            exit('Error: Could not load database driver ' . $driver . '!');
        }

        $capsule = new Capsule;

        $capsule->addConnection([
            'driver' => $driver,
            'host' => $hostname,
            'database' => $database,
            'username' => $username,
            'password' => $password,
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => $prefix,
            'port' => $port
        ], $name);

        // Set the event dispatcher used by Eloquent models... (optional)
        $capsule->setEventDispatcher(new Dispatcher(new Container));

        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();

        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $capsule->bootEloquent();

        $this->capsule = $capsule;

        $this->connection = $this->capsule->schema($this->name)->getConnection();
    }

    public function query($sql)
    {
        if ($this->checkIsSelect($sql)) {
            $return = $this->connection->getPdo()->query($sql);

            $data = $return->fetchAll();

            $result = new \stdClass();
            $result->num_rows = $return->rowCount();
            $result->row = isset($data[0]) ? $data[0] : array();
            $result->rows = $data;

            $this->countAffected = $return->rowCount();

        } else {
            $result = $this->execute($sql);
            $this->countAffected = $result;
            $result = (bool)$result;
        }

        return $result;
    }

    public function execute($sql)
    {
        return $this->connection->affectingStatement($sql);
    }

    public function escape($value)
    {
        return $value;
        return $this->connection->quote($value);
    }

    public function countAffected()
    {
        return $this->countAffected;
    }

    public function getLastId()
    {
        return $this->connection->getPdo()->lastInsertId();
    }

    /**
     * Pega a conexao do banco
     * @param string $name
     * @return \Illuminate\Database\Connection
     */
    public function getConnection($name = 'default')
    {
        return $this->capsule->getConnection($name);
    }

    /**
     * Verifica se o sql é uma consulta ou uma execucao
     * @param $sql
     * @return bool true to select query
     */
    private function checkIsSelect($sql)
    {
        return (substr(strtoupper(trim($sql)), 0, 6) == 'SELECT');
    }

}