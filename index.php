<?php
include 'yla.php';

$dsn = "pgsql:host=localhost;dbname=knykanen";
$user = "db_knykanen";
$pass = getenv("DB_PASSWORD");
$options = [PDO::ATTR_ERRMODE=> PDO::ERRMODE_EXCEPTION];

//if (isset($_POST["syote"])) {
try {
	$yhteys = new PDO($dsn, $user, $pass, $options);
	if (!$yhteys) { 
        echo "Database connection refused.";
        die(); 
    }
} catch (PDOException $e) {
	echo $e->getMessage();
	die();
}
//}
?>

<p>Tällä palvelulla voit testata sql-hakukomentoja ja noutaa tietoja maailman valtioista. </br>
Syötä alla olevaan kenttään sql-hakukomento ja paina nappia.</p>
<form method=POST action=index.php>
  Kirjoita komento:
  <br><textarea rows=15 cols=100 name=syote></textarea>
  <br><br>
  <input type=submit value='HAE'>
</form>


<?php
if (isset($_POST['syote'])) {;
    $syote = $_POST['syote'];
} else {
    die();
}

//validiointi
$fail = 0;

$forbidden = array('CREATE', 'DELETE', 'DROP', 'TRUNCATE', 'TRUNC', 
                    'INSERT', 'UPDATE', 'COPY', 'GRANT', 'REVOKE',
                    'PROCEDURE', 'FUNCTION', 'RETURNS');

$syote = trim($syote);      //tyhjät merkit pois alusta ja lopusta
if (strtoupper(substr($syote, 0, 6)) !='SELECT')
{
    $fail = 1;
}

foreach ($forbidden as $sana) {
    if (strpos(strtoupper($syote), trim(strtoupper($sana)) ) )
    {
        $fail = 2;
    }
}

//ei tulosteta mitään, jos tyhjä syöte
if (strlen($syote)==0) {
    die();
}

//tulostus, jos syötteessä kiellettyjä sanoja
if ($fail) {
    echo "Query too complex!";
    die();
}

$lask = 0;      //monesko rivi tulostuksessa menossa
$colnum = 0;    //sarakkeiden lkm

try {
$tulos = $yhteys->query($syote);    //jos kysely on väärä, ei tulosteta mitään, ei edes virhetekstiä

} catch (PDOException $e) {
	//echo $e->getMessage();
	die();
}

echo "<table>";
try {   
    while($rivi = $tulos->fetch(PDO::FETCH_ASSOC)) {
        //otsikot
        if ($lask == 0)     // ei vielä tulostettu riviäkään
        {
            echo "<tr>";
            $colhead = array_keys($rivi);   //sarakeotsikot
            $colnum = count($colhead);
            for($x=0; $x<$colnum; $x++) {
                echo "<th>" . $colhead[$x] . "</th>";
            }
        }

        echo "</tr><tr>";
        for($x=0; $x<$colnum; $x++) {
            echo "<td>" . $rivi[$colhead[$x]] . "</td>";
        }
        echo "</tr>";
        $lask++;
    }
    
echo "</table>";

} catch (PDOException $e) {
	echo $e->getMessage();
	die();
}

?>

</body></html>

