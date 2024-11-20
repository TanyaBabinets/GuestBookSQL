

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

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $name=trim($_POST["name"]);
    $city=trim($_POST["city"]) ?:null;
    $email = trim($_POST['email']) ?: null;
    $url = trim($_POST["url"]) ?: null;
    $msg=trim($_POST["msg"]);

    if(empty($name) || empty($msg)){
        $error="Имя и сообщение обязательно к заполнению";
    }else {
        try {

            $stmt = $db->prepare("INSERT INTO guest(name, city, email, url, msg, puttime, hide)
            VALUES(:name, :city, :email, :url, :msg, NOW(), 'show')");

$stmt->execute([
    ':name' => $name,
    ':city' => $city,
    ':email' => $email,
    ':url' => $url,
    ':msg' => $msg,
]);

            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } catch (PDOException $e) {
            $error = $e->getMessage();  // Устанавливаем ошибку
        }
    }
}
////////////////////////////////////////
try{
    $stmt = $db->query("SELECT * FROM guest WHERE hide = 'show' ORDER BY puttime DESC");

$messages=$stmt->fetchAll(PDO::FETCH_ASSOC);  
}catch(PDOException $e){ 
die( "No messages".$e->getMessage());
}
?>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html;UTF-8">
    <title></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div id="container">
    <div class="img"><img src="hotel2.jfif" alt="hotel"></div>
    <div class="img"><img src="hotel1.jfif" alt="hotel"></div>
    <div class="img"><img src="hotel3.jfif" alt="hotel"></div>
</div>
<h1>Гостевая книга</h1>
<div id="guestpage">


<form id="myForm" method="post" action="">
    <p style="font-family: 'Bebas Neue Cyrillic'; font-size:18px; color:darkviolet;">Хочу оставить отзыв</p>
    <label for="name">Введите ваше имя:</label>
    <input type="text" placeholder="Заполните поле" id="name" name="name" required><br>
    <label for="city">Введите ваш город:</label>
    <input type="text" id="city" name="city"><br>
    <label for="email">Введите ваш EMAIL:</label>
    <input type="text" id="email" name="email">
    <label for="tel">Введите ваш телефон:</label>
    <input type="text" id="tel" name="url">
    <label for="msg">Сообщение:</label>
    <input type="text" placeholder="Заполните поле" id="msg" name="msg" required>
    <br>
    <button type="submit">Отправить</button><br>
    <span id="successMessage" style="color:green; font-size:24px"></span>
</form>

<?php if ($success): ?>
    <div style="font-style:italic; font-size: 20px;">
        <?php echo $success; ?>
    </div>

<?php endif; ?>

<div id="otzivi">
<h2>Отзывы</h2>
<?php
if(!empty($messages)) {
foreach ($messages as $message){?>
<div class="review">
<p>
    <strong>
<?php
echo htmlentities($message['name']) . (!empty($message['city']) ? " (" . htmlentities($message['city']) . ")" : "");
?></strong></p>
    <p style="font-style:italic; font-size: 20px">
<?php echo htmlentities($message['msg']);?>
</p>

<?php
if(!empty($message['answer'])):
?>

<p><strong>Ответ администратора:</strong>
<?php echo htmlentities($message['answer']);
?></p><br>

<?php endif; ?>

<p style="font-style: italic"><small>Добавлено:

         <?php echo date("Y-m-d H:i", strtotime($message['puttime'])); ?>
    </small></p>
</div>




<?php }
 }else{ ?>
<p>NET otzivov</p>
<?php }
?>

</body>
</html>
