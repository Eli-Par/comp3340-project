<?php
//Db connection info
$db_host = 'localhost';
$db_username = 'root';
$db_password = 'mysql';
$db_name = 'travel_tipia';

//Connect to the database with the connection info above
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

$dbConnected = true;
$dbError = '';

if ($conn->connect_error) {
    $dbConnected = false;
    $dbError = $conn->connect_error;
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Status | Travel Tipia</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="favicon.ico" type="image/x-icon">

    <style>
        /* basic status page styling (embedded to keep status page simple so it is less likely to go down) */
        body {
            font-family: Arial, sans-serif;
            margin: 1.5rem;
        }

        .status {
            margin-bottom: 1em;
        }

        .ok {
            color: green;
        }

        .fail {
            color: red;
        }
    </style>
</head>

<body>
    <h1>Travel Tipia Status</h1>

    <!-- display database status as established in php code above -->
    <div class="status">
        <strong>Database Connection:</strong>
        <span class="<?php echo $dbConnected ? 'ok' : 'fail' ?>">
            <?php echo $dbConnected ? 'Connected' : 'Failed: ' . htmlspecialchars($dbError) ?>
        </span>
    </div>

    <!-- show server status as established in javascript code below -->
    <div class="status">
        <strong>Server status:</strong>
        <span id="fetch-status">Checking...</span>
    </div>

    <script>
        //Fetch index.php, if it is available set status as online, otherwise set as offline
        fetch('index.php', { method: 'GET' })
            .then(response => {
                const fetchStatus = document.getElementById('fetch-status');
                if (response.ok) {
                    fetchStatus.textContent = 'Online';
                    fetchStatus.className = 'ok';
                } else {
                    fetchStatus.textContent = `Offline (HTTP ${response.status})`;
                    fetchStatus.className = 'fail';
                }
            })
            .catch(error => {
                const fetchStatus = document.getElementById('fetch-status');
                fetchStatus.textContent = 'Offline error: ' + error;
                fetchStatus.className = 'fail';
            });
    </script>
</body>

</html>