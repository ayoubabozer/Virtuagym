<?php
/**
 * Created by PhpStorm.
 * User: itay
 * Date: 13/05/19
 * Time: 11:01
 */

namespace classes\Config;

class DB
{
    protected $db_name = 'virtuagym';
    protected $db_user = 'root';
    protected $db_pass = 'root';
    protected $db_host = 'localhost';

    protected $con;

    public function connect()
    {
        try {

            $this->con = new \PDO("mysql:host=$this->db_host;dbname=$this->db_name", $this->db_user, $this->db_pass);
            return $this->con;
        }
        catch( PDOException $Exception ) {

            throw new MyDatabaseException( $Exception->getMessage( ) , $Exception->getCode( ) );
        }
    }

    public function close()
    {
        $this->con = null;
    }
}