<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="styles.css">
</head>
<body>


<?php
include 'yla.php';

$syote = $_POST['syote'];

$dsn = "pgsql:host=localhost;dbname=knykanen";
$user = "db_knykanen";
$pass = getenv("DB_PASSWORD");
$options = [PDO::ATTR_ERRMODE=> PDO::ERRMODE_EXCEPTION];

try {
	$yhteys = new PDO($dsn, $user, $pass, $options);
	if (!$yhteys) { die(); }

    $kysely = "$syote";
    $lause=$yhteys->prepare($kysely);
    
    $lause->execute();

    //tulostus while-lauseessa
    echo "<table style='width:80%'>\n";
    echo '<tr style="text-align:left">';
    
    echo "<th>continent</th>";
    echo "<th>country</th>";
    echo "<th>gdp_percapita</th>";
    echo "<th>area</th>";
    echo "<th>pop</th>";
    echo "</tr>\n";

    while ($tulos = $lause->fetchObject() ) {
        echo "<tr><td>";
        echo $tulos->continent;
        echo "</td><td>";
        echo $tulos->country;
        echo "</td><td>";
        echo $tulos->gdp_percapita;
        echo "</td><td>";
        echo $tulos->area;
        echo "</td><td>";
        echo $tulos->pop;
        echo "</tr>";
}
echo "</table>";
echo "<br>";

} catch (PDOException $e) {
	echo $e->getMessage();
	die();
}

?>

<input type="submit" value="Palaa etusivulle" <a href="#" onclick="history.back();"></a>


</body></html>