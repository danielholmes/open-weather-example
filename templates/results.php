<?php include '_header.php' ?>
<h2>Results for <?php echo htmlspecialchars($forecast->getName()) ?>, <?php echo htmlspecialchars($forecast->getCountry()) ?></h2>

<h3>Current Weather</h3>
<p><?php echo join(', ', $current) ?></p>
<hr />

<h3>3 Day Forecast</h3>
<table>
    <thead>
        <tr>
            <th>Temperature</th>
            <th>Min Temperature</th>
            <th>Max Temperature</th>
            <th>State</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($forecast->getDayForecasts() as $day): ?>
            <tr>
                <td><?php echo $day->getTemperature() ?></td>
                <td><?php echo $day->getMinTemperature() ?></td>
                <td><?php echo $day->getMaxTemperature() ?></td>
                <td><?php echo $day->getState() ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
<hr />

<a href="/">Do Another Search</a>
<?php include '_footer.php' ?>