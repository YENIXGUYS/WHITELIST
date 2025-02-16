<?php
    header('Content-Type: text/plain');

    
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'yenixontops';
    $secretkey = isset($_GET["SecretKeys"]) ? $_GET["SecretKeys"] : '';
    $hwid = isset($_GET["HardwareID"]) ? $_GET["HardwareID"] : '';
    $valuekey = array();
    $valuehwid = array();
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Server down waiting service man to fix" . $conn->connect_error); 
    }

    $query = "SELECT * FROM datax"; 
    $result = mysqli_query($conn, $query); 

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) { 
            $valuekey[] = $row["secretkey"];
            $valuehwid[] = $row["hwid"];
        } 
    }

    if (in_array($secretkey, $valuekey, true)) {
        $updated_hwid = false;
    
        foreach ($valuekey as $index => $key) {
            if ($key === $secretkey) {
                if ($valuehwid[$index] === 'Unknown') {
                    $valuehwid[$index] = $hwid;
                    $updated_hwid = true;
                    $query = "UPDATE datax SET hwid='$hwid' WHERE secretkey='$secretkey'";
                    if(mysqli_query($conn, $query)) {
                        if ($valuehwid[$index] == $hwid) {
                            echo "Complete";
                        } else {
                            echo "Hwid Changed";
                        }
                    } else {
                        echo "Error updating HWID: " . mysqli_error($conn);
                    }
                } else {
                    if ($valuehwid[$index] == $hwid) {
                        echo "Complete";
                    } else {
                        echo "Hwid Found";
                        $updated_hwid = true;
                    }
                }
                break;
            }
        }
    } else {
        echo "Invald key";
    }
    
    mysqli_close($conn);
?>