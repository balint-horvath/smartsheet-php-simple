<?php

set_time_limit(0);

header("Content-type: text/html; chardet=utf-8");

require '../../SimpleSmartsheet.php';

$SheetID = "";
$APIKey = "";

$SS = new SimpleSmartsheet($APIKey);

?>
<!doctype html>
<html>
    <head>
        <title>Documentation</title>
    </head>
    <body>
        <?=$SS->genHTMLDoc($SheetID, 0, 1, 2)?>
    </body>
</html>