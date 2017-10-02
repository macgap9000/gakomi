<?php

echo "<pre>";
echo '<b>$_POST:</b><br>';
print_r($_POST);
echo "</pre>";

//echo "<br><br><br><br><br><br><br><br><br><br><br><br>";

// header('Content-type: text/json');
echo "<pre>";
echo json_encode($_POST, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
echo "</pre>";

?>