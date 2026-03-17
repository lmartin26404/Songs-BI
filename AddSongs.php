<!DOCTYPE html>
<html>
    <title>Add Songs</title>

    <body>

    <?php

include 'ServerFunctions.php';
include 'DatabaseConnection.php';

   $db = new DatabaseConnection("localhost", "root", "Password@MySQL", "songDatabase");
$conn = $db->connect();

ParseSongData($conn);

?>
    </body>
</html>

