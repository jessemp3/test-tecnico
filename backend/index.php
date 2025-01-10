<?php


use Controllers\FilmController;
use Controllers\PersonagensController;

// Configurações de CORS e Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once "Config/Database.php";

use Config\Database;
require_once "Models/Film.php";
require_once "Controllers/FilmController.php";
require_once "Controllers/PersonagensController.php"; // Adicionar esta linha

function handleError($message, $code = 404) {
    http_response_code($code);
    echo json_encode([
        'status' => 'error',
        'message' => $message
    ]);
    exit();
}

try {
    $requestUri = $_SERVER['REQUEST_URI'];
    $requestMethod = $_SERVER['REQUEST_METHOD'];

    $database = Database::getInstance();
    $db = $database->getConnection();
    $filmController = new FilmController($db);
    $personagensController = new PersonagensController();

    $basePath = '/teste-tecnico/backend';
    $path = str_replace($basePath, '', parse_url($requestUri, PHP_URL_PATH));
    $pathParts = explode('/', trim($path, '/'));

    // Remove 'api' do path se existir
    if ($pathParts[0] === 'api') {
        array_shift($pathParts);
    }

    // Log para debug
    error_log("Request URI: " . $requestUri);
    error_log("Path: " . $path);
    error_log("Method: " . $requestMethod);

    // Roteamento
    switch ($requestMethod) {
        case 'GET':
            if (empty($pathParts[0])) {
                handleError("Endpoint não especificado", 400);
            }
            
            if ($pathParts[0] === 'films') {
                if (isset($pathParts[1])) {
                    $filmController->show($pathParts[1]);
                } else {
                    $filmController->index();
                }
            }
            // Nova rota para personagens
            else if ($pathParts[0] === 'people') {
                if (isset($pathParts[1])) {
                    $personagensController->show($pathParts[1]);
                } else {
                    $personagensController->index();
                }
            }
            else {
                handleError("Endpoint não encontrado", 404);
            }
            break;

        case 'POST':
            if ($pathParts[0] === 'films' && $pathParts[1] === 'cover') {
                $filmController->uploadCover();
            } else {
                handleError("Endpoint não encontrado", 404);
            }
            break;

        default:
            handleError("Método não permitido", 405);
    }
} catch (Exception $e) {
    handleError($e->getMessage(), 500);
}