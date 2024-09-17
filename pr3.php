<html>

<head>
   <meta charset="UTF-8">
   <title>Пирамидка</title>
</head>

<body>
   <?php
   $str = "<p>";
   for ($i = 2; $i < 10; $i++) {
    $str .= str_repeat((string) $i, $i) . "</br>";
   }
   echo $str . "</p>";
   ?>
</body>

</html>