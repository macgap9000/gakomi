<?php

echo "<pre>";
echo '<b>$_GET:</b><br>';
print_r($_GET);
echo "</pre>";

// header('Content-type: text/json');
echo "<pre>";
echo json_encode($_GET, JSON_PRETTY_PRINT);
echo "</pre>";

// ?>

<!-- <html>
<head>

    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        table, th, td {
            border: 1px solid black;
        }
    </style>

</head>

<body>

    <table>
        <tr style="height:32px;">
            <th>
                <b>$_GET:</b>
            </th>
            <th>
                <b>JSON:</b>
            </th>
        </tr>

        <tr>
            <td style="vertical-align:text-top;">
                <?php
                    echo "<pre>";
                    print_r($_GET);
                    echo "</pre>";
                ?>
            </td>
            <td style="vertical-align:text-top;">
                <?php
                    echo "<pre>";
                    echo "<br>";
                    //echo json_encode($_GET, JSON_PRETTY_PRINT);
                    $json = json_encode($_GET);
                    $array = json_decode($json);
                    print_r($array);
                    echo "</pre>";  
                ?>
            </td>
        </tr>

    </table>

</body>
</html> -->