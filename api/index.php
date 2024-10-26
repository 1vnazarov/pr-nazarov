<?php

ob_start();

require_once 'db_connect.php';
require_once 'diploma.php';
require_once 'qualification.php';
require_once 'role.php';
require_once 'user.php';

$DB = connect();

// Заголовки CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    return http_response_code(200);
}

// Функция для отправки ответа
function sendResponse($statusCode, $message = '', $data = [])
{
    ob_clean();
    http_response_code($statusCode);
    if ($message) $data['message'] = $message;
    echo json_encode($data);
    exit;
}

// Общая функция для обработки запросов
function handleRequest($class, $action, $DB, $id = null)
{
    if (!class_exists($class)) {
        sendResponse(404, 'Ресурс не найден');
    }
    $data = json_decode(file_get_contents("php://input"), true);
    switch ($action) {
        case 'create':
            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                sendResponse(405, 'Используйте метод POST');
            }
            $instance = new $class(!empty($_POST) ? $_POST : $data);
            $errors = $instance->validation();
            if ($errors) {
                sendResponse(422, 'Ошибка валидации', ['errors' => $errors]);
            }
            if ($instance->create($DB)) {
                sendResponse(201, 'Успешно создано');
            } else {
                sendResponse(507, 'Не удалось сохранить данные');
            }
            break;

        case 'get_all':
            if ($_SERVER['REQUEST_METHOD'] != 'GET') {
                sendResponse(405, 'Используйте метод GET');
            }
            $items = $class::get_all($DB);
            sendResponse(200, null, $items);
            break;

        case 'get_by_id':
            if ($_SERVER['REQUEST_METHOD'] != 'GET') {
                sendResponse(405, 'Используйте метод GET');
            }
            $item = $class::get_by_id($DB, $id);
            if ($item) {
                sendResponse(200, null, $item);
            } else {
                sendResponse(404, 'Не найдено');
            }
            break;

        case 'update':
            if ($_SERVER['REQUEST_METHOD'] != 'PUT') {
                sendResponse(405, 'Используйте метод PUT');
            }
            $instance = new $class($data);
            $errors = $instance->validation(false);
            if ($errors) {
                sendResponse(422, 'Ошибка валидации', ['errors' => $errors]);
            }
            if ($instance->update($DB, $id)) {
                sendResponse(204);
            } else {
                sendResponse(507, 'Не удалось обновить данные');
            }
            break;

        case 'delete':
            if ($_SERVER['REQUEST_METHOD'] != 'DELETE') {
                sendResponse(405, 'Используйте метод DELETE');
            }
            if ($class::delete($DB, $id)) {
                sendResponse(204);
            } else {
                sendResponse(404, 'Не найдено');
            }
            break;
    }
}

// Обработка маршрутов
$queryString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
parse_str($queryString, $queryParams);
$requestUri = explode('/', trim($queryParams['q'], '/'));
$className = ucfirst($requestUri[0]); // Первая часть URL — это имя класса
$action = 'get_all'; // Изначально действие - получение всех элементов
$id = null;

$actionMethods = [
    'POST' => 'create',
    'PUT' => 'update',
    'DELETE' => 'delete',
    'GET' => 'get_by_id'
];

$requestMethod = $_SERVER['REQUEST_METHOD'];
if (array_key_exists($requestMethod, $actionMethods)) {
    $action = $actionMethods[$requestMethod];
    if (in_array($action, ['update', 'delete', 'get_by_id'])) {
        if (isset($requestUri[1]) && is_numeric($requestUri[1])) {
            $id = $requestUri[1];
        } else {
            sendResponse(400, 'ID не указан для ' . ($action === 'update' ? 'обновления' : ($action === 'delete' ? 'удаления' : 'получения')));
        }
    }
}

if (in_array($action, ['create', 'get_all', 'get_by_id', 'update', 'delete'])) {
    handleRequest($className, $action, $DB, $id);
} else {
    sendResponse(404, 'Неизвестное действие');
}