<?php

namespace Models;

use DateTime;
use PDOException;

class Film {
    private $conn;
    private $table_name = "films";
    private $api_url = "https://swapi-node.vercel.app/api/films/"; // URL correta
    private $people_api_url = "https://swapi-node.vercel.app/api/people/";
    private $character_cache = [];

    public function __construct($db) {
        $this->conn = $db;
    }

    private function calculateAge($release_date) {
        $release = new DateTime($release_date);
        $now = new DateTime();
        $interval = $release->diff($now);

        return [
            'years' => $interval->y,
            'months' => $interval->m,
            'days' => $interval->d
        ];
    }

    private function fetchCharacterName($url) {
        // Extrair ID do personagem da URL
        $characterId = basename($url);
        
        // Verificar cache
        if (isset($this->character_cache[$characterId])) {
            return $this->character_cache[$characterId];
        }

        try {
            // Buscar da API de personagens
            $characterUrl = $this->people_api_url . $characterId;
            $response = @file_get_contents($characterUrl);
            
            if ($response === false) {
                throw new \Exception("Erro ao buscar personagem");
            }

            $characterData = json_decode($response, true);
            $name = $characterData['name'] ?? 'Unknown';
            
            // Armazenar no cache
            $this->character_cache[$characterId] = $name;
            
            return $name;
        } catch (\Exception $e) {
            error_log("Erro ao buscar personagem: " . $e->getMessage());
            return "Nome não disponível";
        }
    }

    private function logRequest($endpoint, $params = []) {
        $sql = "INSERT INTO api_logs (endpoint, parameters, request_date) VALUES (?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$endpoint, json_encode($params)]);
    }

    /**
     * Save cover image for a film
     * @param int $episodeId
     * @param string $imageData
     * @param string $imageType
     * @return bool
     * @throws PDOException
     */
    public function saveCoverImage($episodeId, $imageData, $imageType) {
        try {
            if (empty($episodeId) || empty($imageData) || empty($imageType)) {
                throw new \InvalidArgumentException("Parâmetros inválidos");
            }

            $sql = "INSERT INTO films (episode_id, title, cover_image, cover_image_type) 
                    VALUES (?, ?, ?, ?) 
                    ON DUPLICATE KEY UPDATE cover_image = ?, cover_image_type = ?";
            
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute([$episodeId, 'Título Temporário', $imageData, $imageType, $imageData, $imageType]);
        } catch (PDOException $e) {
            throw new PDOException("Erro ao salvar imagem: " . $e->getMessage());
        }
    }

    public function getCoverImage($episodeId) {
        $sql = "SELECT cover_image, cover_image_type FROM films WHERE episode_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$episodeId]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getAllFilms() {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $this->api_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => [
                    'Accept: application/json',
                    'Content-Type: application/json'
                ],
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false
            ]);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            
            if (curl_errno($curl)) {
                throw new \Exception("Erro CURL: " . curl_error($curl));
            }
            
            curl_close($curl);

            if ($httpCode !== 200) {
                throw new \Exception("API retornou código HTTP: " . $httpCode);
            }

            $data = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Erro ao decodificar JSON: " . json_last_error_msg());
            }

            return $data;

        } catch (\Exception $e) {
            error_log("Erro em getAllFilms: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get film details by ID
     * @param int $id
     * @return array|null
     * @throws PDOException
     */
    public function getFilmDetails($id) {
        try {
            if (!$id) {
                throw new \Exception("ID do filme não fornecido");
            }

            $response = file_get_contents($this->api_url);
            if ($response === false) {
                throw new \Exception("Erro ao acessar a API");
            }

            $data = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Erro ao decodificar JSON");
            }

            if ($data && isset($data['results'])) {
                $film = null;
                foreach ($data['results'] as $result) {
                    if ($result['fields']['episode_id'] == $id) {
                        $film = $result;
                        break;
                    }
                }

                if ($film) {
                    if (!isset($film['fields']['release_date'])) {
                        throw new \Exception("Data de lançamento não encontrada");
                    }

                    // Calcula a idade do filme
                    $age = $this->calculateAge($film['fields']['release_date']);
                    
                    // Adiciona a idade aos campos do filme
                    $film['fields']['age'] = [
                        'years' => $age['years'],
                        'months' => $age['months'],
                        'days' => $age['days'],
                        'simple' => $age['years'] . ' anos'
                    ];

                    // Retorna no formato padrão da API
                    return [
                        'status' => 'success',
                        'data' => $film
                    ];
                }
            }

            throw new \Exception("Filme não encontrado");
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
}