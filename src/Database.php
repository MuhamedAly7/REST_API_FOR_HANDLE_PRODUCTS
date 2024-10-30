<?php

class Database
{
    public function __construct(private string $host, 
                                private string $db_name, 
                                private string $username, 
                                private string $password)
    {
           
    }

    public function getConnetion() : PDO
    {
        $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8";
        return new PDO($dsn, $this->username, $this->password);
    }
}