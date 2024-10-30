<?php

class ProductController
{
    public function __construct(private $Gateway)
    {
        
    }

    public function processRequest(string $method, ?string $id) : void
    {
        if($id)
        {
            $this->processResourceRequest($method, $id);
        }
        else
        {
            $this->processCollectionRequest($method);
        }
    }

    private function processResourceRequest(string $method, string $id) : void
    {
        switch ($method)
        {
            case "GET":

                break;
            case "PATCH":

                break;
            case "DELETE":

                break;
            default:
                http_response_code(501);
                echo json_encode([]);
                break;
        }
    }

    private function processCollectionRequest(string $method) : void
    {
        switch ($method)
        {
            case "GET":
                echo json_encode([
                    "id" => 123
                ]);
                break;
            default:

                break;
        }
    }
}