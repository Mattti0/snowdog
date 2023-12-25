<?php
require_once 'config.php';
require_once 'gitversion.php';

define('DEBUG_PRINT', TRUE);

// Define a class for location
class Location {
    public $lat;
    public $lon;
    public $alt;
    public $speed;
    public $ts;
    public $in_area;
    private $valid = FALSE;
    private $conn;
    private $sql;


    function __construct($lat, $lon, $alt, $speed, $ts, $in_area) {
        $utcDateTime = new DateTime($ts, new DateTimeZone('UTC'));
        // Set the Helsinki time zone for conversion
        $helsinkiTimeZone = new DateTimeZone('Europe/Helsinki');
        $utcDateTime->setTimezone($helsinkiTimeZone);
        
        // Format the local time
        $formattedTs = $utcDateTime->format('Y-m-d H:i:s');

        $this->lat = $lat;
        $this->lon = $lon;
        $this->alt = $alt;
        $this->speed = $speed;
        $this->ts = $formattedTs;//$conn->real_escape_string($ts);
        $this->in_area = $in_area;
        $this->valid = TRUE;
    }

    function storeLocation() {
        if($this->valid == TRUE) {
            $conn = new mysqli(SERVER_NAME, USERNAME, PASSWORD, DBNAME);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            // realescape values
            $this->lat = $conn->real_escape_string($this->lat);
            $this->lon = $conn->real_escape_string($this->lon);
            $this->alt = $conn->real_escape_string($this->alt);
            $this->speed = $conn->real_escape_string($this->speed);
            $this->ts = $conn->real_escape_string($this->ts);
            $this->in_area = $conn->real_escape_string($this->in_area);

            $sql = "INSERT INTO location_history (lat, lon, alt, speed, ts, in_area) VALUES ($this->lat, $this->lon, $this->alt, $this->speed, '$this->ts', '$this->in_area')";
                
            // Execute the query
            $conn->query($sql);
            $rows = $conn->affected_rows;
            // Close the connection
            $conn->close();
            return $rows;
        }
    }
}

function queryHelper($sql) {
    $conn = new mysqli(SERVER_NAME, USERNAME, PASSWORD, DBNAME);
    $result = $conn->query($sql);
    $conn->close();
    return $result;
}

// Create a simple SVG image of a dog
function generateDogSvg($text) {
    $img = '<svg viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000">';
    if(DEBUG_PRINT == TRUE) {
        $img = $img.'<text x="0" y="100" font-family="Courier" font-size="16" fill="black">'.$text.'</text>';
    }
    $img = $img.'<g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M432.4 453.5l-17 46.7h34.4z" fill="#FFFFFF"></path><path d="M725.3 259.7H312.2c-16.5 0-30 13.5-30 30v413.1c0 16.5 13.5 30 30 30h413.1c16.5 0 30-13.5 30-30V289.7c0-16.6-13.5-30-30-30z m-98.8 164.5h25.4V550h-25.4V424.2z m-116.5 0h40.8c15.5 0 25.5 0.6 30.2 1.9 7.2 1.9 13.2 6 18.1 12.3 4.9 6.3 7.3 14.5 7.3 24.5 0 7.7-1.4 14.2-4.2 19.5s-6.4 9.4-10.7 12.4c-4.3 3-8.7 5-13.2 6-6.1 1.2-14.8 1.8-26.4 1.8h-16.6V550H510V424.2z m-90.7 0h26.9L496.5 550h-27.6l-11-28.6h-50.3L397.2 550h-27l49.1-125.8z m229.1 273.3H352.6c-19.4 0-35.1-15.7-35.1-35.1v-295c0-5.5 4.5-10 10-10s10 4.5 10 10v295c0 8.3 6.8 15.1 15.1 15.1h295.8c5.5 0 10 4.5 10 10s-4.4 10-10 10z" fill="#FFFFFF"></path><path d="M569.4 479.2c3.4-1.3 6-3.4 7.9-6.2 1.9-2.8 2.9-6.1 2.9-9.8 0-4.6-1.3-8.4-4-11.3-2.7-3-6.1-4.8-10.2-5.6-3-0.6-9.1-0.9-18.3-0.9h-12.3v35.7h13.9c10 0.1 16.7-0.6 20.1-1.9z" fill="#FFFFFF"></path><path d="M648.4 677.5H352.6c-8.3 0-15.1-6.8-15.1-15.1v-295c0-5.5-4.5-10-10-10s-10 4.5-10 10v295c0 19.4 15.7 35.1 35.1 35.1h295.8c5.5 0 10-4.5 10-10s-4.4-10-10-10z" fill="#05ff65"></path><path d="M865 386.5c11 0 20-9 20-20s-9-20-20-20h-69.7v-56.8c0-38.6-31.4-70-70-70h-27.8v-67.3c0-11-9-20-20-20s-20 9-20 20v67.3H611v-67.3c0-11-9-20-20-20s-20 9-20 20v67.3h-46.5v-67.3c0-11-9-20-20-20s-20 9-20 20v67.3H438v-67.3c0-11-9-20-20-20s-20 9-20 20v67.3h-85.8c-38.6 0-70 31.4-70 70v56.8h-69.7c-11 0-20 9-20 20s9 20 20 20h69.7V433h-69.7c-11 0-20 9-20 20s9 20 20 20h69.7v46.5h-69.7c-11 0-20 9-20 20s9 20 20 20h69.7V606h-69.7c-11 0-20 9-20 20s9 20 20 20h69.7v56.8c0 38.6 31.4 70 70 70H343v72.5c0 11 9 20 20 20s20-9 20-20v-72.5h46.5v72.5c0 11 9 20 20 20s20-9 20-20v-72.5H516v72.5c0 11 9 20 20 20s20-9 20-20v-72.5h46.5v72.5c0 11 9 20 20 20s20-9 20-20v-72.5h82.8c38.6 0 70-31.4 70-70V646H865c11 0 20-9 20-20s-9-20-20-20h-69.7v-46.5H865c11 0 20-9 20-20s-9-20-20-20h-69.7V473H865c11 0 20-9 20-20s-9-20-20-20h-69.7v-46.5H865zM755.3 702.7c0 16.5-13.5 30-30 30H312.2c-16.5 0-30-13.5-30-30v-413c0-16.5 13.5-30 30-30h413.1c16.5 0 30 13.5 30 30v413z" fill="#283957"></path><path d="M407.6 521.4h50.3l11 28.6h27.6l-50.4-125.8h-26.9l-49 125.8h27l10.4-28.6z m24.8-67.9l17.3 46.7h-34.3l17-46.7zM535.4 502.6H552c11.5 0 20.3-0.6 26.4-1.8 4.5-1 8.9-3 13.2-6 4.3-3 7.9-7.1 10.7-12.4s4.2-11.8 4.2-19.5c0-10-2.4-18.2-7.3-24.5-4.9-6.3-10.9-10.4-18.1-12.3-4.7-1.3-14.8-1.9-30.2-1.9H510V550h25.4v-47.4z m0-57.1h12.3c9.2 0 15.2 0.3 18.3 0.9 4.1 0.7 7.5 2.6 10.2 5.6 2.7 3 4 6.8 4 11.3 0 3.7-1 7-2.9 9.8-1.9 2.8-4.6 4.9-7.9 6.2-3.4 1.3-10.1 2-20.1 2h-13.9v-35.8zM626.5 424.2h25.4V550h-25.4z" fill="#283957"></path></g></svg>';

    return $img;
}

function generateFailImage() {
    $img = '<svg viewBox="0 0 1024 1024" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="#000000">
    <g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M432.4 453.5l-17 46.7h34.4z" fill="#FFFFFF"></path><path d="M725.3 259.7H312.2c-16.5 0-30 13.5-30 30v413.1c0 16.5 13.5 30 30 30h413.1c16.5 0 30-13.5 30-30V289.7c0-16.6-13.5-30-30-30z m-98.8 164.5h25.4V550h-25.4V424.2z m-116.5 0h40.8c15.5 0 25.5 0.6 30.2 1.9 7.2 1.9 13.2 6 18.1 12.3 4.9 6.3 7.3 14.5 7.3 24.5 0 7.7-1.4 14.2-4.2 19.5s-6.4 9.4-10.7 12.4c-4.3 3-8.7 5-13.2 6-6.1 1.2-14.8 1.8-26.4 1.8h-16.6V550H510V424.2z m-90.7 0h26.9L496.5 550h-27.6l-11-28.6h-50.3L397.2 550h-27l49.1-125.8z m229.1 273.3H352.6c-19.4 0-35.1-15.7-35.1-35.1v-295c0-5.5 4.5-10 10-10s10 4.5 10 10v295c0 8.3 6.8 15.1 15.1 15.1h295.8c5.5 0 10 4.5 10 10s-4.4 10-10 10z" fill="#FFFFFF"></path><path d="M569.4 479.2c3.4-1.3 6-3.4 7.9-6.2 1.9-2.8 2.9-6.1 2.9-9.8 0-4.6-1.3-8.4-4-11.3-2.7-3-6.1-4.8-10.2-5.6-3-0.6-9.1-0.9-18.3-0.9h-12.3v35.7h13.9c10 0.1 16.7-0.6 20.1-1.9z" fill="#FFFFFF"></path><path d="M648.4 677.5H352.6c-8.3 0-15.1-6.8-15.1-15.1v-295c0-5.5-4.5-10-10-10s-10 4.5-10 10v295c0 19.4 15.7 35.1 35.1 35.1h295.8c5.5 0 10-4.5 10-10s-4.4-10-10-10z" fill="#ff0505"></path><path d="M865 386.5c11 0 20-9 20-20s-9-20-20-20h-69.7v-56.8c0-38.6-31.4-70-70-70h-27.8v-67.3c0-11-9-20-20-20s-20 9-20 20v67.3H611v-67.3c0-11-9-20-20-20s-20 9-20 20v67.3h-46.5v-67.3c0-11-9-20-20-20s-20 9-20 20v67.3H438v-67.3c0-11-9-20-20-20s-20 9-20 20v67.3h-85.8c-38.6 0-70 31.4-70 70v56.8h-69.7c-11 0-20 9-20 20s9 20 20 20h69.7V433h-69.7c-11 0-20 9-20 20s9 20 20 20h69.7v46.5h-69.7c-11 0-20 9-20 20s9 20 20 20h69.7V606h-69.7c-11 0-20 9-20 20s9 20 20 20h69.7v56.8c0 38.6 31.4 70 70 70H343v72.5c0 11 9 20 20 20s20-9 20-20v-72.5h46.5v72.5c0 11 9 20 20 20s20-9 20-20v-72.5H516v72.5c0 11 9 20 20 20s20-9 20-20v-72.5h46.5v72.5c0 11 9 20 20 20s20-9 20-20v-72.5h82.8c38.6 0 70-31.4 70-70V646H865c11 0 20-9 20-20s-9-20-20-20h-69.7v-46.5H865c11 0 20-9 20-20s-9-20-20-20h-69.7V473H865c11 0 20-9 20-20s-9-20-20-20h-69.7v-46.5H865zM755.3 702.7c0 16.5-13.5 30-30 30H312.2c-16.5 0-30-13.5-30-30v-413c0-16.5 13.5-30 30-30h413.1c16.5 0 30 13.5 30 30v413z" fill="#283957"></path><path d="M407.6 521.4h50.3l11 28.6h27.6l-50.4-125.8h-26.9l-49 125.8h27l10.4-28.6z m24.8-67.9l17.3 46.7h-34.3l17-46.7zM535.4 502.6H552c11.5 0 20.3-0.6 26.4-1.8 4.5-1 8.9-3 13.2-6 4.3-3 7.9-7.1 10.7-12.4s4.2-11.8 4.2-19.5c0-10-2.4-18.2-7.3-24.5-4.9-6.3-10.9-10.4-18.1-12.3-4.7-1.3-14.8-1.9-30.2-1.9H510V550h25.4v-47.4z m0-57.1h12.3c9.2 0 15.2 0.3 18.3 0.9 4.1 0.7 7.5 2.6 10.2 5.6 2.7 3 4 6.8 4 11.3 0 3.7-1 7-2.9 9.8-1.9 2.8-4.6 4.9-7.9 6.2-3.4 1.3-10.1 2-20.1 2h-13.9v-35.8zM626.5 424.2h25.4V550h-25.4z" fill="#283957"></path></g></svg>';
    return $img;
}


switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $request = $_GET;
        break;
    case 'POST':
        $request = $_POST;
        break;
    default:
        $request = null;
        break;
}

// Check if it's a GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET' 
        && count($_GET) == 2 
        && isset($_GET['api_key']) 
        && ($_GET['api_key'] === APIKEY || $_GET['api_key'] === APIKEY) 
        && !isset($_GET['geojson'])) {

    header('Content-Type: image/svg+xml');
    echo generateDogSvg(json_encode($_GET));
}
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' 
        && count($_GET) == 1
        && isset($_GET['version'])) {

    header('Content-Type: application/json');
    echo json_encode(array('version' => GIT_REVISION));
}
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['api_key']) && isset($_GET['geojson'])) {

    $result = queryHelper('SELECT DISTINCT in_area, MAX(ts) AS latest_ts FROM location_history GROUP BY in_area;');

    if ($result->num_rows > 0) {

        $json = [];
        while ($row = $result->fetch_assoc()) {
            array_push($json, array('in_area' => $row['in_area'], 'latest_ts' => $row['latest_ts']));
        }

    } else {
        echo "{}";
    }

    header('Content-Type: application/json');
    echo json_encode($json);
}
elseif ($_SERVER['REQUEST_METHOD'] === 'GET' 
        && count($_GET) == 2 
        && isset($_GET['api_key'])
        && isset($_GET['lastonline'])) {

    $result = queryHelper('SELECT max(ts) as ts FROM `location_history`');
    if ($result->num_rows > 0) {

        $json = [];
        while ($row = $result->fetch_assoc()) {
            $json = array('online' => $row['ts']);
        }
    } else {
        echo "{}";
    }

    header('Content-Type: application/json');
    echo json_encode($json);

}
// {'lat': 62.8059224833, 'lon': 22.9163893333, 'alt': 44.2, 'speed': 0, 'ts': '2023-12-21T09:55:47.000Z', 'api_key': '0028b076-ca97-44c5-9603-bdfc38e2718e', 'in_area': 'Kertunlaakso'}
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode(file_get_contents("php://input"), true);

    // Check if the request data is valid
    if ($requestData !== null && ($requestData['api_key'] === APIKEY || $requestData['apikey'] === APIKEY)) {
        if(isset($requestData['lat']) && isset($requestData['lon']) && isset($requestData['alt']) && isset($requestData['speed']) && isset($requestData['ts']) && isset($requestData['in_area'])) {
            $location = new Location(
                $requestData['lat'],
                $requestData['lon'],
                $requestData['alt'],
                $requestData['speed'],
                $requestData['ts'],
                $requestData['in_area']
            );
            
            if($location->storeLocation() != 0) {
                echo json_encode(array('status' => 'Ok'));
            }
            else {
                echo json_encode(array('status' => 'Error'));
            }

        }
        else {

        }
    } else {
        // If the request data is not valid, return an error
        http_response_code(400); // Bad Request
        echo json_encode(array('status' => 'Not all fields set'));
    }
}
else {
    header('Content-Type: image/svg+xml');
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: 0");
    echo generateFailImage();
}
?>
