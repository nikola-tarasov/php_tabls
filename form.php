<?php

try {
    $db = new PDO('mysql:dbname=testcrmalfa;host=localhost', 'testcrmalfa', 'testcrmalfa500005');
} catch (Throwable $e) {
    echo 'Ошибка создания подключения к базе: ' . $e->getMessage(); // отлавливаем ошибку подключения к базе
    exit();
}

// 1. Записываем переданные из формы ФИО и телефон пользователя. Если ничего не передали, то ничего не записываем
$name = $_POST['name'] ?? ''; // тернарный оператор
$phone = $_POST['phone'] ?? '';

$userId = $_GET['id'] ?? null; // используем тернарный оператор
$delete = $_GET['delete'] ?? null;
$edit = $_GET['edit'] ?? null;

// если у нас нет в суперглобальном массиве $_GET элемента id, то значит мы добавляем нового пользователя
if (!$userId && $name && $phone) {
    $result = $db->exec("INSERT INTO users (name, phone) VALUES ('{$name}', '{$phone}')");

    // делаем редирект на главную страницу
    header("Location: index.php");
    exit();
}

$action = 'form.php'; // это экшен формы по умолчанию
$textButton = 'Добавить'; // текст кнопки по умолчанию

if ($userId && !$delete && !$edit) {
    // Если в суперглобальном массиве $_GET пришел только id пользователя, то просто получаем данные пользователя
// и загружаем форму
    $result = $db->query("SELECT * FROM users WHERE id = {$userId}")->fetchAll();
    if (isset($result[0])) {
        $name = $result[0]['name'];
        $phone = $result[0]['phone'];

        //добавляем к экшену формы дополнительные параметры, чтобы сформировать ссылку на редактирование
        $action .= '?id=' . $userId . '&edit=1';
        $textButton = 'Изменить';
    }
} elseif ($userId && $edit) {
    // Если в суперглобальном массиве $_GET передан и id пользователя, и параметр edit, то нам надо обновить пользователя
    $result = $db->query("UPDATE `users` SET name = '{$name}', phone = '{$phone}' WHERE id = {$userId}")->execute();

    // делаем редирект на главную страницу
    header("Location: index.php");
    exit();
} elseif ($userId && $delete) {
    // Если в суперглобальном массиве $_GET передан и id пользователя, и параметр delete, то нам надо удалить пользователя
    $db->query("DELETE FROM `users` WHERE id= {$userId}");

    // делаем редирект на главную страницу
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Добавление нового контакта</title>
</head>
<body>

<div class="content">
<div class="form">
<form action="<?= $action; ?>" method="post">
    <p>ФИО
        <input type="text" value="<?= $name; ?>" name="name" placeholder="Введите ФИО контакта"
               maxlength="50" required/></p>
    <p>Телефон
        <input type="text" value="<?= $phone; ?>" name="phone" placeholder="Введите телефон контакта"
               maxlength="30"  required/></p>
    <input type="submit" value="<?= $textButton; ?>">
    <input type="button" onclick="history.back();" value="Назад"/>
</div>
</div>
</body>
</html>