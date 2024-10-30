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
            $row['is_available'] = (bool)$row['is_available']; 
            $data[] = $row;
        }
        return $data;
    }

    public function create(array $data):string
    {
        $sql = "INSERT INTO product (name, size, is_available) VALUES (:name, :size, :is_available)";
        $stat = self::$connection->prepare($sql);
        $stat->bindValue(":name", $data['name'], PDO::PARAM_STR);
        $stat->bindValue(":size", $data['size'] ?? 0, PDO::PARAM_INT);
        $stat->bindValue(":is_available", (bool)$data['is_available'] ?? false, PDO::PARAM_BOOL);
        $stat->execute();
        return self::$connection->lastInsertId();
    }

    public function get(string $id) : array|false
    {
        $sql = "SELECT * FROM product WHERE id = :id";
        $stat = self::$connection->prepare($sql);
        $stat->bindValue(":id", $id, PDO::PARAM_INT);
        $stat->execute();
        $data = $stat->fetch(PDO::FETCH_ASSOC);
        if($data !== false) {
            $data['is_available'] = (bool) $data['is_available'];
        }
        return $data;
    }

    public function update(array $current, array $new) : int
    {
        $sql = "UPDATE product SET name = :name, size = :size, is_available = :is_available WHERE id = :id";
        $stat = self::$connection->prepare($sql);
        $stat->bindValue(":name", $new['name'] ?? $current['name'], PDO::PARAM_STR);
        $stat->bindValue(":size", $new['size'] ?? $current['size'], PDO::PARAM_INT);
        $stat->bindValue(":is_available", $new['is_available'] ?? $current['is_available'], PDO::PARAM_BOOL);
        $stat->bindValue(":id", $current['id'], PDO::PARAM_INT);
        $stat->execute();
        return $stat->rowCount();
    }

    public function delete(string $id)
    {
        $sql = "DELETE FROM product WHERE id = :id";
        $stat = self::$connection->prepare($sql);
        $stat->bindValue(":id", $id, PDO::PARAM_INT);
        $stat->execute();
        return $stat->rowCount();
    }
}