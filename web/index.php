<?php

$submittedLocation = false;
$locationValid = true;
$location = null;
if (isset($_GET['location']))
{
    $submittedLocation = true;
    $location = $_GET['location'];
    if ($location !== 'Sydney AU')
    {
        $locationValid = false;
        http_response_code(400);
    }
}

?>
<!doctype html>
<html lang=en-us>
<head>
    <meta charset=utf-8 />
    <title>Open Weather Example</title>
</head>
<body>
<?php if ($location !== null && $locationValid): ?>

<?php else: ?>
    <?php if (!$locationValid): ?>
        <div>The location you entered &quot;<?php echo $location ?>&quot; could not be found</div>
    <?php endif ?>
    <form action="" method="get">
        <label for="locationField">Enter a location in the format "City CountryCode". e.g. "Sydney AU":</label>
        <input id="locationField" type="text" name="location" value="<?php echo $location ?>" />
        <input type="submit" value="Get Weather" />
    </form>
<?php endif ?>
</body>
</html>