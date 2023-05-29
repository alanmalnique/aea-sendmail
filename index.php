<?php

declare(strict_types=1);

$post = file_get_contents('php://input');
$fields = json_decode($post, true) ?: [];
$template = isset($_GET['type']) ? $_GET['type'] : '';

require('vendor/autoload.php');
require('env.php');

use App\Sender\EmailSender;
use App\Validator\ValidatorProvider;

function setResponseHeaders(int $httpStatusCode) {
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    http_response_code($httpStatusCode);
}

try {
    ValidatorProvider::validate($template, $fields);
    $emailSender = new EmailSender($fields);
    $sendMail = $emailSender->send($template);
} catch (Throwable $e) {
    setResponseHeaders(400);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage(),
        'trace' => $e->getTrace()
    ]);
    die;
}

setResponseHeaders(200);
echo json_encode(['error' => false]);