<?php

// Gets the data from the Dashboard and...

require_once "AnalysisData.php";
require_once "DatabaseConnection.php";
require_once "ServerFunctions.php";


function Data()
{
 

    $object = $_POST['object'] ?? 'day';
    $artist = $_POST['band'] ?? 'TOOL';
    $graph = $_POST['graph'] ?? '';
    
    $keysArray = [];
    $valuesArray = [];

    // Used for a Vertical bar, horizontal bar, pie, donut
    function nongroup()
    {
        global $keysArray, $valuesArray;

        // Makes the database connection
        $db = new DatabaseConnection("localhost", "root", "Password@MySQL", "songDatabase");
        $conn = $db->connect();

        $sql = "delete from songs_return where key_col <> '' and value_col <> '' and artist <> '';";
        $result = $conn->query($sql);

        $option = "one";
        $object = $_POST['object'] ?? 'day';
        $artist = $_POST['band'] ?? 'TOOL';
       

        // Finds the objects
        FindCarCount($option, $object, $artist, "");

        // Gets the values that have been found. They are put into a SQL table.
        $sql = "SELECT key_col, value_col from songs_return";
        $result = $conn->query($sql);

        // Resets the values to be empty.
        $keysArray = [];
        $valuesArray = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $keysArray[] = $row["key_col"];
                $valuesArray[] = $row["value_col"];
            }
        }

        // Closes the connection.
        $conn->close();
    }
    function group()
    {
        global $keysArray, $valuesArray, $artistArray, $totalString;

                // Makes the database connection
        $db = new DatabaseConnection("localhost", "root", "Password@MySQL", "songDatabase");
        $conn = $db->connect();


        $sql = "delete from songs_return where key_col <> '' and value_col <> '' and artist <> '';";
        $conn->execute_query($sql);

        $option = "one";
        $object = $_POST['object'] ?? 'day';
        $bandOne = $_POST['band'] ?? 'TOOL';
        $bandTwo = $_POST['bandTwo'] ?? '';

        $group = "group";
        // Finds the objects
        FindCarCount($option, $object, $bandOne, "");
        GetValues($conn);



        // Finds the new artist
        FindCarCount($option, $object, $bandTwo, $group);
        GetValues($conn);

        $sql = "select * from songs_return;";
        $result = $conn->query($sql);

        $counter = 0;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $keysArray[] = $row["key_col"];
                $valuesArray[] = $row["value_col"];
                $artistArray[] =  $row["artist"];

                //echo $keysArray[$counter] . " " . $valuesArray[$counter] . " ";

                $counter = $counter + 1;
            }
        }

        // Closes the connection.
        $conn->close();


    }

    
    if($graph != 'group' && $graph != 'stack' && $graph != 'hStack' && $graph != 'hGroup')
    {
        nongroup();
    }
    else
    {
        group();
    }


    $db = new DatabaseConnection("localhost", "root", "Password@MySQL", "songDatabase");
    $conn = $db->connect();

    $currentArtist = FindCurrentArtist($conn);
    $songCount = FindSongCount($conn, $artist);

    $conn->close();
}

function GetValues($conn)
{
        // Gets the values that have been found. They are put into a SQL table.
    $sql = "SELECT key_col, value_col, artist from songs_return";
    $result = $conn->query($sql);

    // Resets the values to be empty.
    $keysArray = [];
    $valuesArray = [];
    $twoArtist = [];

    $counter = 0;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $keysArray[] = $row["key_col"];
            $valuesArray[] = $row["value_col"];
            $twoArtist[] =  $row["artist"];

            $counter = $counter + 1;
        }
    }
}

function RecieveData()
{
     global $keysArray, $valuesArray;
     
        $db = new DatabaseConnection("localhost", "root", "Password@MySQL", "songDatabase");
        $conn = $db->connect();

        // Gets the values that have been found. They are put into a SQL table.
        $sql = "SELECT key_col, value_col, artist from songs_return";
        $result = $conn->query($sql);

        // Resets the values to be empty.
        $keysArray = [];
        $valuesArray = [];
        $twoArtist = [];

        $counter = 0;

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $keysArray[] = $row["key_col"];
                $valuesArray[] = $row["value_col"];
                $twoArtist[] =  $row["artist"];

                $counter = $counter + 1;
            }
        }

        $conn->close();

        echo json_encode([
            "x" => $keysArray,
            "y" => $valuesArray,
            "z" => $twoArtist
        ]);

    
}

function SearchboxArtistValues()
{
    $resultSearch = "";

    if(isset($_POST['search']))
    {
        $search = $_POST['search'];

        $db = new DatabaseConnection("localhost", "root", "Password@MySQL", "songDatabase");
        $conn = $db->connect();

        // Gets the value out of a search bar to then look for them in the database. It uses the SQL keyword like to find it.
        $stmt = $conn->prepare("select distinct artist from songs_data where artist like ?;");
        $searchItem = '%' . $search . '%';

        // Do not want to bring up results if the search bar is empty.
        if ($search != "") {
            $stmt->bind_param("s", $searchItem);
            $stmt->execute();

            $result = $stmt->get_result();

            $counter = 0;

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Limits to a max of five results of artist in the results.
                    if ($counter >= 5) {
                        break;
                    } else {
                        // The output of the searches.
                        $resultSearch .=  '<br> <button style="background-color:dimgray; min-width:100%; " onclick="ShowArtistButtonClick(' . $counter . ')" id=' . $counter . '>' . $row["artist"] . '</button> ';
                        $counter = $counter + 1;
                    }
                }
            }
        }

        $stmt->close();
        $conn->close();


        echo $resultSearch;
        exit();
    }
}

 
$option = $_POST['option'] ?? '';




if($option == '0')
{
    SearchboxArtistValues();
}
else{
    data();
    RecieveData();

}




?>