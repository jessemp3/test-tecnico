<?php

namespace Controllers;

use Models\Film;
use \Exception;

class FilmController {
    protected $film;

    public function __construct($db) {
        $this->film = new Film($db);
    }

    public function index() {
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

    public function show($id) {
        try {
            $film = $this->film->getFilmDetails($id);
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'data' => $film
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

    public function uploadCover() {
        try {
            // Validar se recebeu os dados necessários
            if (!isset($_FILES['cover']) || !isset($_POST['episode_id'])) {
                throw new \InvalidArgumentException('Dados inválidos para upload');
            }

            $episodeId = filter_var($_POST['episode_id'], FILTER_VALIDATE_INT);
            if (!$episodeId) {
                throw new \InvalidArgumentException('ID do episódio inválido');
            }

            // Validar arquivo
            $file = $_FILES['cover'];
            $allowedTypes = ['image/jpeg', 'image/png'];
            if (!in_array($file['type'], $allowedTypes)) {
                throw new \InvalidArgumentException('Tipo de arquivo não permitido');
            }

            // Ler imagem
            $imageData = file_get_contents($file['tmp_name']);
            if (!$imageData) {
                throw new \RuntimeException('Erro ao ler arquivo');
            }

            // Salvar imagem
            $saved = $this->film->saveCoverImage($episodeId, $imageData, $file['type']);
            
            if ($saved) {
                header('Content-Type: application/json');
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Imagem salva com sucesso'
                ]);
            } else {
                throw new \RuntimeException('Erro ao salvar imagem');
            }

        } catch (\Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}