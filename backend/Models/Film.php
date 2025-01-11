<?php

namespace Models;

use PDOException;

class Film
{
    private $conn;
    // private $table_name = "films";
    private $api_url = "https://swapi-node.vercel.app/api/films/";

    public function __construct($db)
    {
        $this->conn = $db;
        $this->api_url = "https://swapi-node.vercel.app/api/films/";
    }

    private function logRequest($endpoint, $params = [])
    {
        try {
            $sql = "INSERT INTO api_logs (endpoint, request_date) 
                    VALUES (?, NOW())";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $endpoint
            ]);

            return true;
        } catch (\PDOException $e) {
            error_log("Erro ao registrar log: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Save cover image for a film
     * @param int $episodeId
     * @param string $imageData
     * @param string $imageType
     * @return bool
     * @throws PDOException
     */


    public function getAllFilms()
    {
        try {
            $this->logRequest('/api/films', [
                'action' => 'getAllFilms',
                'timestamp' => date('Y-m-d H:i:s')
            ]);

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
    public function getFilmDetails($id)
    {
        try {
            $this->logRequest('/api/films/' . $id, [
                'film_id' => $id
            ]);

            $response = file_get_contents($this->api_url);
            $data = json_decode($response, true);

            if ($data && isset($data['results'])) {
                foreach ($data['results'] as $result) {
                    if ($result['fields']['episode_id'] == $id) {
                        return [
                            'status' => 'success',
                            'data' => $result
                        ];
                    }
                }
            }

            return [
                'status' => 'error',
                'message' => 'Filme não encontrado'
            ];
        } catch (\Exception $e) {
            throw new \Exception("Erro ao buscar filme: " . $e->getMessage());
        }
    }
}