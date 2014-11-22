<?php include '_header.php' ?>
<?php if ($errorMessage !== null): ?>
    <div class="error"><?php echo htmlspecialchars($errorMessage) ?></div>
    <hr />
<?php endif ?>
<form action="" method="get">
    <label for="locationField">Enter a location in the format "City CountryCode". e.g. "Sydney AU":</label>
    <input id="locationField" type="text" name="location" value="<?php echo htmlspecialchars($location) ?>" required="required" />
    <input type="submit" value="Get Weather" />
</form>
<?php include '_footer.php' ?>