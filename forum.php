<?php


require("./includes/common.inc.php");
require("./includes/config.inc.php");
                                                                     
ta($_POST);
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Newsforum</title>
    <link rel="stylesheet" href="./css/common.css">
</head>

<body>
    <h1>Newsforum</h1>
    <form method="post">
        <input type="submit" value="ausloggen" name="btnlogout">
    </form>
</body>

</html>