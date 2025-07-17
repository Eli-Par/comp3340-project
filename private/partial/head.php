<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Funnel+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<link rel="stylesheet" href="global.css">
<?php
require __DIR__ . '/../dbConnection.php';

$headThemeFile = 'regular'; // fallback

$headThemeStmt = $conn->prepare('SELECT theme FROM themes LIMIT 1');
$headThemeStmt->execute();
$headThemeResult = $headThemeStmt->get_result();

if ($headThemeResult->num_rows > 0) {
    $headThemeRow = $headThemeResult->fetch_assoc();
    if ($headThemeRow['theme'] != '') {
        $headThemeFile = $headThemeRow['theme'];
    }
}
?>

<link rel="stylesheet" href="themes/<?php echo htmlspecialchars($headThemeFile); ?>.css">

<link rel="icon" href="favicon.ico" type="image/x-icon">

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />