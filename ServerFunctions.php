<?php

use function Safe\mysql_real_escape_string;

    // ConnectDB - Connects to the database.

    // Note: The information it is checking is the Spotify API


    // SongExist - Checks the SQl database and checks if the
    //  song exists or not. It returns true if it is and false 
    //  if the song does not exist.
    function SongExist($conn, $title,$artist,$lrycis)
    {

        // Gets at most one row of data and at mininum zero rows.
        // It takes in the year, title and artist name. It does not take 
        // in the genere incase if a song gets re-relased. This could be 
        // arguged some I might have to thing about that more.
        // It compares all the information to the song.
        
        // Breaks out the information so that it can be in the SQL statement.
        $_title     = mysqli_real_escape_string($conn,$title);
        $_artist    = mysqli_real_escape_string($conn, $artist);

       //$sql = "SELECT 1 from songs_data where year = " . $year . 
        //    " and title ='$_title' and firstName = '$_firstName' and lastName = '$_lastName';";
        
        // MIGHT WANT TO CHANGE THIS LATER WHEN I GET MORE DATA
        $sql = "SELECT 1 from songs_data where title = '$_title' and artist = '$_artist';";

        $result = $conn->query($sql);

        // The number of rows in the table is 1 so the song exists.
        // Therefore, do not add the song.
        if($result->num_rows >= 1)
        {
            return true;
        }

        // The song does not exist in the database. Therefore, download the data
        // and then add the song to the database.
        else
        {
            //DownloadSong($title, $firstName);
            AddSong($conn, $title,$artist,$lrycis);
            return false;
        }
    }

    // DownloadManySongs - Downloads a ton of songs using the Genius API
    // wrote in Python to download many songs and then add them to the 
    // database
    function DownloadManySongs()
    {

    }

    // DownloadSong - Uses a Python file to download songs using the 
    // Genius API. 
    function DownloadSong($title, $firstName)
    {
        // Calls a Python file to download the song.

        // --- We will know the title and the singer ---
    
        // Writes to the JSON file
        $output = shell_exec("python3 ./downloadSong.py \"$title\"  \"$firstName\" ");
       // echo $output;
    }

    // ParseSongData - Pares the data that was used to get the songs data
    // and breaks down the information and adds it to the database.
    function ParseSongData($conn)
    {
        $lyrics = "";
        $artist = "";
        $title = "";

        // Open the text file
        $fh = fopen('AllSongs.txt','r');

        while(!feof($fh))
        {
            $currentLine = fgets($fh);
            
            // End of the song
            if (trim($currentLine) == "<|endoftext|>" || trim($currentLine) == "ERROR")
            {
            
                // Adds the song to the database
                if (trim($currentLine) == "<|endoftext|>")
                {
                    // Cleans the lyrics to remove un-needed works
                    $lyrics = str_replace("<|endoftext|>","",$lyrics);
                    $lyrics = str_replace("ERROR","",$lyrics);

                    SongExist($conn, $title,$artist,$lyrics);
                }

                // Resets the variables
                $artist = "";
                $title = "";
                $lyrics = "";
            }

            
            // Compares the current line to the song title if it is a new line
            if (substr(trim($currentLine), 0, 11) == "Song title:")
            {
                $title = trim(substr($currentLine, 12));
            }
            else if(substr(trim($currentLine),0,7) == "Artist:")
            {
                $artist = trim(substr($currentLine,8));
            }
         
            else
            {
                // Adds the current line to the lyrics.
                $lyrics = $lyrics . "\n" . $currentLine;
            }
        }

        fclose($fh);

    }

    // AddSong - Adds the song to the my SQL database. The song is only added if 
    // it does not already exist. Which is done by another function.
    // A song has a song tilte, a singer (first name and last name) 
    // a year, genere and lyrics.
    function AddSong($conn, $title,$artist,$lrycis)
    {
        $stmt = $conn->prepare("INSERT INTO songs_data (title,artist,lyrics) VALUES (?,?,?)");
        $stmt->bind_param("sss",$title,$artist,$lrycis);
        $stmt->execute();
        $stmt->close();
    }

    // RemoveDuplicate - Removes duplicate songs in the database. There is no need to have
    // multiple songs in the database this will cause data to be wrong.
    // Such as double counting data that should not be there.
    function RemoveDuplicate()
    {

    }

    // TransportData - Writes to a SQL table which is used to transport the data that the Python program
    // found to the PHP file. Which is then used to make the graphs. There are two columns in the table.
    // They are key (which are the objects) and then the values (which are the counts). An example would
    // be Cars: Ford -> 254   Chevy -> 170  Lexus -> 132, ...
    function TransportData()
    {

    }

function findSongCount(mysqli $conn, string $artist): int
{
    if($artist == "all")
    {
        $stmt = $conn->prepare("SELECT COUNT(title) AS cnt FROM songs_data");
        
    }
    else
    {
        $stmt = $conn->prepare("SELECT COUNT(title) AS cnt FROM songs_data WHERE artist = ?");
        $stmt->bind_param('s', $artist);
    }

    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count;
}


    Function FindCurrentArtist($conn)
    {
        $result = $conn->query("select artist from lookup_table;");
        $currentArtist = "";

        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $currentArtist = $row["artist"];
                echo "THE CURRENT ARTIST IS " . $currentArtist;

                return $currentArtist;
            }
        }
    }

?>