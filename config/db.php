<?php
return [
    'class' => \yii\db\Connection::class,
    'dsn' => 'mysql:host=mysql;dbname=' . $_ENV['MYSQL_DATABASE'],
    'username' => $_ENV['MYSQL_USER'],
    'password' => $_ENV['MYSQL_PASSWORD'],
    'charset' => 'utf8',
];
