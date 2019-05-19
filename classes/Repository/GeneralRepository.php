<?php

namespace classes\Repository;
use classes\Mail\Mail;
use classes\Config\DB;

class GeneralRepository
{
    protected $mail;
    protected $con;

    public function __construct()
    {
        $this->mail = new Mail();

        $db = new DB();

        $this->con = $db->connect();

    }

    public function addHistory($form_data)
    {
        $query = "INSERT INTO api_history (endpoint, method, request, response)
                                VALUES(:endpoint, :method, :request, :response)";

        $statement = $this->con->prepare($query);

        if($statement->execute($form_data))
        {
            $data = "insert";
        }
        else {
            $data = "error";
        }

        return $data;
    }

    /**
     * @return Mail
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param Mail $mail
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    }

    /**
     * @return \PDO
     */
    public function getCon()
    {
        return $this->con;
    }

    /**
     * @param \PDO $con
     */
    public function setCon($con)
    {
        $this->con = $con;
    }




}