<?php 
return [
    'host' => 'sql202.infinityfree.com',
    'database' => 'if0_40557224_loceria_melchorita',
    'username' => 'if0_40557224',
    'password' => '12345678nicolem',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
