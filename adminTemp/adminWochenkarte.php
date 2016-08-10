<?php
include "../config.php";
$dayWords = array(1 => "Montag", 2 => "Dienstag", 3 => "Mittwoch", 4 => "Donnerstag", 5 => "Freitag", 6 => "Samstag", 7 => "Sonntag", 99 => "");

$dbConn = new mysqli(SQL_HOST, SQL_USER, SQL_PASS, SQL_DB);
$dbConn->query("SET NAMES 'utf8'");
$dateSource = new DateTime();

if (!empty($_POST)) {
    $dbStartDate = new DateTime($_POST['startDate']);
    $dbEndDate = new DateTime($_POST['endDate']);
    $dbWerbetext = $dbConn->real_escape_string($_POST['werbetext']);
    $sql = "UPDATE wochenkarten SET startDate = '".$dbStartDate->format("Y-m-d")."', endDate = '".$dbEndDate->format("Y-m-d")."', werbetext = '".$dbWerbetext."'";
    $dbConn->query($sql);

    foreach($_POST['title'] as $key => $value) {
        $dbDesc = $dbConn->real_escape_string($_POST['description'][$key]);
        $dbPrice = $dbConn->real_escape_string($_POST['price'][$key]);
        $dbUnit = $dbConn->real_escape_string($_POST['unit'][$key]);
        $sql = "UPDATE wochenangebot SET title = '".$value."', description = '$dbDesc', price = '$dbPrice', unit='$dbUnit' WHERE ID = '$key'";
        $dbConn->query($sql);
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
</head>
<body>
    <div class="wrapper">
        <h1>Wochenkarte bearbeiten</h1>
    </div>
    <form action="adminWochenkarte.php" method="POST" accept-charset="UTF-8">
        <?php
        $sql = "SELECT * FROM wochenkarten";
        $pageRes = $dbConn->query($sql);
        $pageInfo = $pageRes->fetch_object();
        $startDate = new DateTime($pageInfo->startDate);
        $endDate = new DateTime($pageInfo->endDate);
        $werbetext = $pageInfo->werbetext;

        $sql = "SELECT * FROM wochenangebot WHERE kartenID = '1' ORDER BY ID ASC ";
        $speisenRes = $dbConn->query($sql);
        echo 'Gültig vom <input type="text" name="startDate" value="'.$startDate->format("d.m.Y").'"/> bis zum <input type="text" name="endDate" value="'.$endDate->format("d.m.Y").'"/>';
        echo '<table>';
        while ($speisen = $speisenRes->fetch_object()) {
            echo '
            <tr>
                <td><input type="text" name="title['.$speisen->ID.']" value="'.$speisen->title.'"/><br><input type="text" name="description['.$speisen->ID.']" value="'.$speisen->description.'"/></td>
                <td><input type="text" name="price['.$speisen->ID.']" value="'.$speisen->price.'" size="10"/> / <input type="text" name="unit['.$speisen->ID.']" value="'.$speisen->unit.'" size="7"/>
                    <input type="hidden" name="type['.$speisen->ID.']" value="'.$speisen->type.'"</td>
            </tr>
            ';
        }
        ?>
            <tr>
                <td colspan="2">
                    <textarea name="werbetext" cols="40" rows="6"><?php echo $werbetext; ?></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2"><input type="submit" value="Speichern" name="submit" />                </td>
            </tr>
        </table>
    </form>
</body>
</html>
