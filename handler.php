<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Домен
$domain = '';

// На какую почту отправлять
$emails = [
    'example@example.com',
];

$subject = 'Тема письма';

$headers = [
    'From: noreply@'.$domain.'\r\n',
    'MIME-Version: 1.0\r\n',
    'Content-Type: text/html; charset=utf-8\r\n'
];

$fields = [
    'name'  => [
        'label' => 'Имя: ',
        'require' => true
    ],
    'email' => [
        'label' => 'Почта: ',
        'require' => true
    ],
    'phone' => [
        'label' => 'Телефон: ',
        'require' => true
    ]
];

$errors = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    response(['error' => 'Bad data!']);
}

foreach ($fields as $field => $params) {
    if (!empty($_POST[$field]) || $params['require'] === false) {
        $message .= $params['label'] . $_POST[$field] . '<br/>';
        continue;
    }
    
    $errors[$field] = 'Обязательно для заполнения';
}

if (!empty($errors)) {
    response($errors);
}

foreach ($emails as $email) {
    mail($email, $subject, $message, $headers);
}

response(['success' => true]);

function response($data) {
    echo json_encode($errors);
    exit;
}
