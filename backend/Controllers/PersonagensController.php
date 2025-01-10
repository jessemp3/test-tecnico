<?php

namespace Controllers;

class PersonagensController {
    private $api_url = "https://swapi-node.vercel.app/api/people/";
    private $cache = [];

    public function __construct() {
    }

    public function index() {
        try {
            $response = @file_get_contents($this->api_url);
            
            if ($response === false) {
                throw new \Exception("Erro ao buscar personagens");
            }

            $data = json_decode($response, true);
            
            echo json_encode([
                "status" => "success",
                "data" => $data
            ]);
        } catch (\Exception $e) {
            $this->handleError($e->getMessage(), 500);
        }
    }

    public function show($id) {
        try {
            if (isset($this->cache[$id])) {
                echo json_encode([
                    "status" => "success",
                    "data" => $this->cache[$id]
                ]);
                return;
            }

            $characterUrl = $this->api_url . $id;
            $response = @file_get_contents($characterUrl);
            
            if ($response === false) {
                throw new \Exception("Erro ao buscar personagem");
            }

            $character = json_decode($response, true);
            
            if (!$character) {
                throw new \Exception("Personagem não encontrado");
            }

            $this->cache[$id] = $character;

            echo json_encode([
                "status" => "success",
                "data" => $character
            ]);
        } catch (\Exception $e) {
            $this->handleError($e->getMessage(), $e->getMessage() === "Personagem não encontrado" ? 404 : 500);
        }
    }

    private function handleError($message, $code = 500) {
        http_response_code($code);
        echo json_encode([
            "status" => "error",
            "message" => $message
        ]);
    }
}