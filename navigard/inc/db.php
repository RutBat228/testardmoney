<?php

// Константы для подключения к базе данных
const HOST = "localhost";      // Хост базы данных
const USER = "ardmoney";       // Имя пользователя
const BAZA = "ardmoney";       // Название базы данных 
const PASS = "64ihufoz";       // Пароль
const TABLE_PREFIX = "navigard_"; // Префикс для таблиц

// Создаем подключение к базе данных
global $connect;
$connect = new mysqli(HOST, USER, PASS, BAZA);
$connect->query("SET NAMES 'utf8'"); // Устанавливаем кодировку UTF-8
