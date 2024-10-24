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

// Общая функция для обработки запросов
function handleRequest($class, $action, $DB, $id = null)
{
    $statusCode = 200;
    $responseMessage = '';
    $responseData = [];

    switch ($action) {
        case 'create':
            if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                $statusCode = 405;
                $responseMessage = 'Используйте метод POST';
                break;
            }
            $instance = new $class($_POST);
            $errors = $instance->validation();
            if ($errors) {
                $statusCode = 422;
                $responseMessage = 'Ошибка валидации';
                $responseData = ['errors' => $errors];
                break;
            }
            if ($instance->create($DB)) {
                $statusCode = 201;
                $responseMessage = 'Успешно создано';
            } else {
                $statusCode = 507;
                $responseMessage = 'Не удалось сохранить данные';
            }
            break;

        case 'get_all':
            if ($_SERVER['REQUEST_METHOD'] != 'GET') {
                $statusCode = 405;
                $responseMessage = 'Используйте метод GET';
                break;
            }
            $items = $class::get_all($DB);
            $responseData = $items;
            break;

        case 'get_by_id':
            if ($_SERVER['REQUEST_METHOD'] != 'GET') {
                $statusCode = 405;
                $responseMessage = 'Используйте метод GET';
                break;
            }
            if ($id === null) {
                $statusCode = 400;
                $responseMessage = 'ID не указан';
                break;
            }
            $item = $class::get_by_id($DB, $id);
            if ($item) {
                $responseData = $item;
            } else {
                $statusCode = 404;
                $responseMessage = 'Не найдено';
            }
            break;

        case 'update':
            if ($_SERVER['REQUEST_METHOD'] != 'PUT') {
                $statusCode = 405;
                $responseMessage = 'Используйте метод PUT';
                break;
            }
            parse_str(file_get_contents("php://input"), $put_vars);
            $instance = new $class($put_vars);
            $errors = $instance->validation();
            if ($errors) {
                $statusCode = 422;
                $responseMessage = 'Ошибка валидации';
                $responseData = ['errors' => $errors];
                break;
            }
            if ($instance->update($DB, $put_vars['id'])) {
                $statusCode = 200;
                $responseMessage = 'Успешно обновлено';
            } else {
                $statusCode = 507;
                $responseMessage = 'Не удалось обновить данные';
            }
            break;

        case 'delete':
            if ($_SERVER['REQUEST_METHOD'] != 'DELETE') {
                $statusCode = 405;
                $responseMessage = 'Используйте метод DELETE';
                break;
            }
            parse_str(file_get_contents("php://input"), $delete_vars);
            if ($class::delete($DB, $delete_vars['id'])) {
                $statusCode = 200;
                $responseMessage = 'Успешно удалено';
            } else {
                $statusCode = 404;
                $responseMessage = 'Не найдено';
            }
            break;
    }

    ob_clean();
    http_response_code($statusCode);
    return json_encode(array_merge(['code' => $statusCode, 'message' => $responseMessage], $responseData));
}

// Обработка маршрутов
$queryString = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
parse_str($queryString, $queryParams);
$requestUri = explode('/', trim($queryParams['q'], '/'));
$className = ucfirst($requestUri[0]); // Первая часть URL — это имя класса
$action = null; // Изначально действие отсутствует
$id = null; // Изначально ID отсутствует

// Определяем действие и ID на основе URL и метода запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = 'create';
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $action = 'update';
    if (isset($requestUri[1]) && is_numeric($requestUri[1])) {
        $id = $requestUri[1]; // Получаем ID из URL
    }
} elseif (isset($requestUri[1])) {
    if (is_numeric($requestUri[1])) { // Если второй элемент - это число, значит, это ID
        $id = $requestUri[1];
        $action = 'get_by_id';
    } else { // Если это не число, то это действие
        $action = $requestUri[1];
    }
} else {
    $action = 'get_all'; // Если нет других действий, то по умолчанию - получение всех элементов
}

// Проверка доступных действий
if (in_array($action, ['create', 'get_all', 'get_by_id', 'update', 'delete'])) {
    echo handleRequest($className, $action, $DB, $id);
} else {
    $status = 404;
    http_response_code($status);
    die(json_encode(['code' => $status, 'message' => 'Неизвестное действие']));
}