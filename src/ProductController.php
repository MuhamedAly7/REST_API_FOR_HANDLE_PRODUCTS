<?php

class ProductController
{
    public function __construct(private ProductGateway $Gateway)
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
        $product = $this->Gateway->get($id);
        
        if(!$product) {
            http_response_code(404);
            echo json_encode([
                "message" => "Product Not Found"
            ]);
            return;
        }

        switch ($method)
        {
            case "GET":
                echo json_encode($product);
                break;
            case "PATCH":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $errors = $this->getValidationErrors($data, false);
                if(!empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    return;
                }
                if(!array_key_exists('is_available', $data)) {
                    $data['is_available'] = false;
                }

                $rows = $this->Gateway->update($product, $data);

                echo json_encode([
                    "message" => "Product $id Updated",
                    "rows" => $rows
                ]);
                break;
            case "DELETE":
                $rows = $this->Gateway->delete($id);
                echo json_encode([
                    "message" => "Product $id Deleted",
                    "rows" => $rows
                ]);
                break;
            default:
                http_response_code(405);
                header("Allow: GET, PATCH, DELETE");
                break;
        }
    }

    private function processCollectionRequest(string $method) : void
    {
        switch ($method)
        {
            case "GET":
                echo json_encode($this->Gateway->getAll());
                break;
            case "POST":
                $data = (array) json_decode(file_get_contents("php://input"), true);
                $errors = $this->getValidationErrors($data);
                if(!empty($errors)) {
                    http_response_code(422);
                    echo json_encode(["errors" => $errors]);
                    return;
                }
                if(!array_key_exists('is_available', $data)) {
                    $data['is_available'] = false;
                }
                $id = $this->Gateway->create($data);
                http_response_code(201);
                echo json_encode([
                    "message" => "Product Created",
                    "id" => $id
                ]);
                break;
            default:
                http_response_code(405);
                header("Allow: GET, POST");
                break;
        }
    }

    private function getValidationErrors(array $data, bool $is_new = true) : array
    {
        $errors = [];
        if($is_new && empty($data['name'])) {
            $errors[] = "name is required";
        }

        if(array_key_exists("size", $data)) {
            if(filter_var($data['size'], FILTER_VALIDATE_INT) === false) {
                $errors = 'size must be integer';
            }
        }

        return $errors;
    }
}