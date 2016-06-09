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
    /******************************************QUERIES*******************************************/
    // TOPIC
    $topicsql = "SELECT * FROM topic";
    $topicresult = $connection -> query($topicsql);

    // SPEAKER
    $speakersql = "SELECT * FROM ref_speaker";
    $speakerresult = $connection -> query($speakersql);

    // BUILDING
    $buildingsql = "SELECT * FROM ref_building";
    $buildingresult = $connection -> query($buildingsql);

    // BUILDINGS && ROOMS
    $buildroomsql =  "SELECT * FROM xref_building_room JOIN(ref_building, ref_room) ON(xref_building_room.building_id = ref_building.building_id AND xref_building_room.room_id = ref_room.room_id)";
    $buildroomresult = $connection -> query($buildroomsql);
    /**************************************WRITE TO JSON**************************************/
    // TOPIC
    if($topicresult -> num_rows > 0){
      $rows = array();
      while($row = mysqli_fetch_assoc($topicresult)){
        $rows[] = $row;
      }
      $fp = fopen("topic.json", "w");
      fwrite($fp, json_encode($rows));
      fclose($fp);
    } else{
      echo "0 results";
    }

    // SPEAKER
    if($speakerresult -> num_rows > 0){
      $rows = array();
      while($row = mysqli_fetch_assoc($speakerresult)){
        $rows[] = $row;
      }
      $fp = fopen("speaker.json", "w");
      fwrite($fp, json_encode($rows));
      fclose($fp);
    } else{
      echo "0 results";
    }

    // BUILDING
    if($buildingresult -> num_rows > 0){
      $rows = array();
      while($row = mysqli_fetch_assoc($buildingresult)){
        $rows[] = $row;
      }
      $fp = fopen("building.json", "w");
      fwrite($fp, json_encode($rows));
      fclose($fp);
    } else{
      echo "0 results";
    }

    // BUILDING && ROOM
    if($buildroomresult -> num_rows > 0){
      $rows = array();
      while($row = mysqli_fetch_assoc($buildroomresult)){
        $rows[] = $row;
      }
      $fp = fopen("buildRoom.json", "w");
      fwrite($fp, json_encode($rows));
      fclose($fp);
    } else{
      echo "0 results";
    }
    /*************************EVENT TABLE (SPECIFIC EVENT ID)*************************/
    // INFO FROM FORM
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    @$eventid = $request->eventId;
    @$title =  $request->title;
    @$eventdate = $request->eventDate;
    @$speaker = $request->speaker;
    @$building = $request->building;
    @$room = $request->room;

    // UPDATE TABLE
    $userdata = "UPDATE events SET title = '$title', event_date = '$eventdate', speaker = '$speaker', building = '$building', room = '$room' WHERE event_id = '$eventid'";
    $userdataresult = $connection -> query($userdata);

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
