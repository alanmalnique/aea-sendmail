<?php

declare(strict_types=1);

$post = file_get_contents('php://input');
$fields = json_decode($post, true) ?: [];
$template = isset($_GET['type']) ? $_GET['type'] : '';

require('vendor/autoload.php');
require('env.php');

use App\Sender\EmailSender;
use App\Validator\ValidatorProvider;

try {
    ValidatorProvider::validate($template, $fields);
    $emailSender = new EmailSender($fields);
    $sendMail = $emailSender->send($template);
} catch (Throwable $e) {
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
    die;
}

echo json_encode(['error' => false]);