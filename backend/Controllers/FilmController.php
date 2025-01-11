<?php

namespace Controllers;

use Models\Film;
use \Exception;

class FilmController
{
    protected $film;

    public function __construct($db)
    {
        $this->film = new Film($db);
    }

    public function index()
    {
        try {
            $films = $this->film->getAllFilms();
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'data' => $films
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function show($id)
    {
        try {
            $result = $this->film->getFilmDetails($id);

            if (!$result) {
                http_response_code(404);
                echo json_encode([
                    "status" => "error",
                    "message" => "Filme não encontrado",
                    "code" => 404
                ]);
                return;
            }

            echo json_encode([
                "status" => "success",
                "data" => $result
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage(),
                "code" => 500
            ]);
        }
    }
}