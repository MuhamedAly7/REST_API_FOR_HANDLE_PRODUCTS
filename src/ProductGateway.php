<?php

class ProductGateway
{
    private static PDO $connection;
    public function __construct(private Database $database)
    {
        self::$connection = $database->getConnetion();   
    }

    public function getAll() : array
    {
        $sql = "SELECT * FROM product";
        $stat = self::$connection->query($sql);
        $data = [];
        while($row = $stat->fetch(PDO::FETCH_ASSOC))
        {
            $data[] = $row;
        }
        return $data;
    }
}