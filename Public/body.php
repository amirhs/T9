
<!DOCTYPE html>
<html lang="en">
<body>

<?php if (isset($Message) === true): ?>
    <div>
        <?php echo $Message; ?>
    </div>
<?php endif ?>

<div style="width: 40%; display: inline-block; margin: 100px 0 0 100px;">

    <table>
        <tr>
            <td>
                <form action="/index.php">
                    <input type="text" name="digit">
                    <input type="submit" name="submit" value="Search">
                </form>
                <br>
            </td>
        </tr>
        <?php
        if (isset($results) === true) {
            foreach ($results as $result) {
                echo '<tr>';
                echo '<td>';
                echo $result->name . ' ' . $result->family . PHP_EOL;
                echo $result->phoneNumber . PHP_EOL;
                echo '</td>';
                echo '</tr>';
            }
        }
        ?>

    </table>

</div>
<div style="width: 40%; height: auto; display: inline-block; margin-top: 100px; float: right;">
    <form action="/saveContact.php" method="get">
        <table>
            <tr>
                <td>
                    <label for="name">Name: </label>
                </td>
                <td>
                    <input type="text" name="name">
                </td>

            </tr>
            <tr>
                <td>
                    <label for="family">Family: </label>
                </td>
                <td>
                    <input type="text" name="family">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="PhoneNumber">Telephone: </label>
                </td>
                <td>
                    <input type="text" name="phoneNumber">
                </td>
            </tr>
            <tr>
                <td>
                    <br>
                    <input type="submit" name="submit" value="Save">
                </td>
            </tr>
        </table>
    </form>

</div>
</body>
</html>