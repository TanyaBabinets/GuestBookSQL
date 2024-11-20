
<?php
global $db;
$success = '';
$error = '';
try {

    $user = "root";
    $pass = "";
    $db = new PDO('mysql:host=localhost;dbname=guest', $user, $pass);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

if($_SERVER["REQUEST_METHOD"] === "POST") {

    $id_msg = $_POST["id_msg"];
    $answer = trim(isset($_POST["answer"]) ? $_POST["answer"] : '');
    $hide = trim(isset($_POST["hide"]) ? trim($_POST["hide"]) : 'show');
//    var_dump($id_msg, $answer, $hide);
//    exit;

    try {

        $stmt = $db->prepare(
            "UPDATE guest 
                 SET answer = :answer, hide = :hide 
                 WHERE id_msg = :id_msg"
        );
        error_log("DEBUG: answer={$answer}, hide={$hide}, id_msg={$id_msg}");
        $stmt->execute([
            ':answer' => $answer,
            ':hide' => $hide,
            ':id_msg' => $id_msg
        ]);


    } catch (PDOException $e) {
        $error = "Ошибка: " . $e->getMessage();
    }
}

///////////////////////////////////////////////////

try{

    $stmt = $db->query("SELECT * FROM guest WHERE hide = 'show' ORDER BY puttime DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
}catch(PDOException $e){
    die("Ошибка: ".$e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Страница администратора</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
<h1>Администрирование гостевой книги</h1>

<?php
if (!empty($error)) echo "<p style='color=red;'>$error</p>";
if (!empty($success)) echo "<p style='color=green;'>$success</p>";
foreach ($messages as $message) :?>
<form method="post">
    <h3><?=htmlentities($message['name'])?></h3>
    <p class="msg"><?=htmlentities($message['msg'])?></p>
    <input type="hidden" name="id_msg" value="<?= $message['id_msg'] ?>">
        <textarea name="answer" placeholder="Ответ администратора" class="answer"><?php echo htmlentities($message['answer']); ?></textarea><br>
    <input type="radio" name="hide" value="show" <?= $message['hide'] === 'show' ? 'checked' : '' ?>>
    <label> Показать </label>

    <input type="radio" name="hide" value="hide" <?= $message['hide'] === 'hide' ? 'checked' : '' ?>>
    <label> Скрыть </label><br>
    <button type="submit" class="button">Сохранить</button>
</form>
<hr>
<?php endforeach; ?>



</html>
</html>