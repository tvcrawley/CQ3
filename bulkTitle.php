    <?php
    /******************************CONNECTING TO SERVER******************************/
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "challenge_db";

    $connection = new mysqli($servername, $username, $password, $database);
    if($connection -> connect_error){
      die("Connection failed: " . $connection -> connect_error);
    }
    /**********************************EVENT TABLE (TITLE)**********************************/
    // INFO FROM FORM
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    @$bulktitle =  $request->bulkTitle;

    // UPDATE TABLE
    $userbulktitle = "UPDATE events SET title = '$bulktitle'";
    $userbulktitleresult = $connection -> query($userbulktitle);

    // QUERY
    $eventsql = "SELECT * FROM events JOIN topic ON events.topic_id = topic.topic_id";
    $eventresult = $connection -> query($eventsql);

    // WRITE TO JSON
    if($eventresult -> num_rows > 0){
      $rows = array();
      while($row = mysqli_fetch_assoc($eventresult)){
        $rows[] = $row;
      }
      $fp = fopen("event.json", "w");
      fwrite($fp, json_encode($rows));
      fclose($fp);
    } else{
      echo "0 results";
    }
    $connection -> close();
    ?>
