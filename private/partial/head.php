<!-- Import google fonts for funnel sans -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Funnel+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

<!-- Setup encoding and mobile support -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<!-- Include global styles -->
<link rel="stylesheet" href="global.css">

<?php
require __DIR__ . '/../dbConnection.php';

$headThemeFile = 'regular'; // fallback theme

//Get current theme from db
$headThemeStmt = $conn->prepare('SELECT theme FROM themes LIMIT 1');
$headThemeStmt->execute();
$headThemeResult = $headThemeStmt->get_result();

//If theme exists set it as the theme
if ($headThemeResult->num_rows > 0) {
    $headThemeRow = $headThemeResult->fetch_assoc();
    if ($headThemeRow['theme'] != '') {
        $headThemeFile = $headThemeRow['theme'];
    }
}
?>

<!-- link the theme found above -->
<link rel="stylesheet" href="themes/<?php echo htmlspecialchars($headThemeFile); ?>.css">

<!-- link the icon -->
<link rel="icon" href="favicon.ico" type="image/x-icon">

<!-- link css for google icons -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

<?php
//Get name of current script
$scriptName = basename($_SERVER['SCRIPT_NAME'], '.php');

//Setup dynamic title
if ($scriptName === 'index') {
    $pageTitle = 'Travel Tipia';
    $currentPageName = 'home page';
} else {
    // Convert underscores to spaces and capitalize words
    $currentPageName = str_replace('_', ' ', $scriptName);
    $pageTitle = ucwords($currentPageName) . ' | Travel Tipia';
}

//Setup keywords
$pageKeywords = $pageKeywords ?? 'travel, explore, adventure, community, tip, hub';
?>

<!-- Setup title, keywords and description based on variables provided -->
<title><?php echo $pageTitle ?></title>
<meta name="description" content="<?php echo htmlspecialchars($pageDescription) ?? 'A travel tip site and discussion board for all your travel needs'; ?>">
<meta name="keywords" content="<?php echo htmlspecialchars($currentPageName) . ', ' . htmlspecialchars($pageKeywords); ?>">