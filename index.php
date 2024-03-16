<?php
try {
    $db = new PDO('mysql:dbname=testcrmalfa;host=localhost', 'testcrmalfa', 'testcrmalfa500005');
} catch (Throwable $e) {
    echo 'Ошибка создания подключения к базе: ' . $e->getMessage(); // отлавливаем ошибку подключения к базе
    exit();
}

$result = $db->query('SELECT * FROM users');

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Справочник</title>
</head>
<body>
    <div class="link">
        <div class="link"><a href="form.php"> + Добавить новый контакт</a></div>
    </div>
<div class="content" >
<table>
    <tr>
        <th>№</th>
        <th>ФИО</th>
        <th>Телефон</th>
        <th>edit</th>
        <th>del</th>
    </tr>
    <?php
    foreach ($result as $value) {
        $editUrl = 'form.php?id=' . $value['id'];
        $deleteUrl = 'form.php?id=' . $value['id'] . '&delete=1';
        echo "<tr>";
        echo "<td>" . $value['id'] . "</td>";
        echo "<td>" . $value['name'] . "</td>";
        echo "<td>" . $value['phone'] . "</td>";
        echo "<td><a href=" . $editUrl . "> edit</a></td>";
        echo "<td><a href=" . $deleteUrl . "> del</a></td>";
        echo "</tr>";
    }
    echo "</table>"
    ?>
</div>
</body>
</html>