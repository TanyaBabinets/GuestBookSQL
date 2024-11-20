<?php
global $db;
try {

    $user = "root";
    $pass = "";
    $db = new PDO('mysql:host=localhost;dbname=guest', $user, $pass);


    $stmt = $db->query("SELECT COUNT(*) FROM guest");
    $count = $stmt->fetchColumn();
    if ($count == 0) {
        $db->exec("INSERT INTO guest VALUES (1,'Anna', 'Kiev', 'anna@gmail.com', '05088866999', 'Thanks','thank you', '2024-10-11 13:55:55', 'show')");
        $db->exec("INSERT INTO guest VALUES (2,'Petr', 'Kiev', 'petr13@gmail.com', '05000866999', 'Not good','thank you', '2024-10-11 11:05:55', 'show')");

    }

    $tables = $db->query('SHOW TABLES');
    $nametable = [];
    while ($row = $tables->fetch(PDO::FETCH_NUM)) {
        $nametable[] = $row[0];
    }
    $n = count($nametable);

    for ($i = 0; $i < $n; $i++) {
        echo "<table border='1' width='100%'>";
        echo "<caption style = 'font-size:20pt;font-weight:bold'>" . $nametable[$i] . "</caption>";
        echo "<thead><tr>";

        $q = "SHOW COLUMNS FROM " . $nametable[$i];
        $sth = $db->query($q);
        $namecolumn = [];
        while ($row = $sth->fetch()) {
            $namecolumn[] = $row['Field'];
            echo "<th>" . htmlentities($row['Field']) . "</th>";
        }

        echo "</tr></thead>";

        $query = "SELECT * FROM " . $nametable[$i];
        $stmt = $db->query($query);


        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";

            foreach ($namecolumn as $columnName) {
                if (isset($row[$columnName])) {
                    echo "<td>" . htmlentities($row[$columnName]) . "</td>";
                } else {
                    echo "<td>&nbsp;</td>";
                }
            }
            echo "</tr>";
        }

        echo "</table><br />";
    }
} catch (PDOException $e) {
    //Вывести сообщение и прекратить выполнение текущего скрипта
    die("Error: " . $e->getMessage());
}
?>
