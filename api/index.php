<?php

ob_start();

require_once 'diploma.php';
require_once 'qualification.php';
require_once 'role.php';
require_once 'user.php';

$requestMethod = $_SERVER['REQUEST_METHOD'];

// Заголовки CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Allow-Methods: *');
header('Content-Type: application/json');

if ($requestMethod == 'OPTIONS') {
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
function handleRequest($class, $action, $id = null)
{
    global $requestMethod;
    if (!class_exists($class)) {
        sendResponse(404, 'Ресурс не найден');
    }
    $data = json_decode(file_get_contents("php://input"), true);
    switch ($action) {
        case 'create':
            if ($requestMethod != 'POST') {
                sendResponse(405, 'Используйте метод POST');
            }
            $instance = new $class(!empty($_POST) ? $_POST : $data);
            $errors = $instance->validation();
            if ($errors) {
                sendResponse(422, 'Ошибка валидации', ['errors' => $errors]);
            }
            $new_item_id = $instance->create();
            if ($new_item_id) {
                sendResponse(201, null, ['id' => $new_item_id]);
            } else {
                sendResponse(507, 'Не удалось сохранить данные');
            }
            break;

        case 'get_all':
            if ($requestMethod != 'GET') {
                sendResponse(405, 'Используйте метод GET');
            }
            $items = $class::get_all();
            sendResponse(200, null, $items);
            break;

        case 'get_by_id':
            if ($requestMethod != 'GET') {
                sendResponse(405, 'Используйте метод GET');
            }
            $item = $class::get_by_id($id);
            if ($item) {
                sendResponse(200, null, $item);
            } else {
                sendResponse(404, 'Не найдено');
            }
            break;

        case 'update':
            if ($requestMethod != 'PATCH') {
                sendResponse(405, 'Используйте метод PATCH');
            }
            $instance = new $class($data);
            $errors = $instance->validation(false);
            if ($errors) {
                sendResponse(422, 'Ошибка валидации', ['errors' => $errors]);
            }
            if ($instance->update($id)) {
                sendResponse(204);
            } else {
                sendResponse(507, 'Не удалось обновить данные');
            }
            break;

        case 'delete':
            if ($requestMethod != 'DELETE') {
                sendResponse(405, 'Используйте метод DELETE');
            }
            if ($class::delete($id)) {
                sendResponse(204);
            } else {
                sendResponse(404, 'Не найдено');
            }
            break;
    }
}

// Обработка маршрутов
$requestUri = array_slice(explode('/', $_SERVER['REQUEST_URI']), 2);
$className = ucfirst($requestUri[0]); // Первая часть URL — это имя класса
$action = 'get_all'; // Изначально действие - получение всех элементов
$id = null;

$actionMethods = [
    'POST' => 'create',
    'PATCH' => 'update',
    'DELETE' => 'delete',
    'GET' => 'get_all'
];

if (array_key_exists($requestMethod, $actionMethods)) {
    $action = $actionMethods[$requestMethod];
    if (isset($requestUri[1]) && is_numeric($requestUri[1])) {
        $id = $requestUri[1];
        if ($action === 'get_all') {
            $action = 'get_by_id';
        }
    } elseif (in_array($action, ['update', 'delete', 'get_by_id'])) {
        sendResponse(400, 'ID не указан для ' . ($action === 'update' ? 'обновления' : ($action === 'delete' ? 'удаления' : 'получения')));
    }
}
else {
    sendResponse(405, "Неизвестный метод");
}

if (in_array($action, ['create', 'get_all', 'get_by_id', 'update', 'delete'])) {
    handleRequest($className, $action, $id);
} else {
    sendResponse(404, 'Неизвестное действие');
}