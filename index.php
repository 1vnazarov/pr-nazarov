<html>

<head>
   <meta charset="UTF-8">
   <title>Пятерочка</title>
</head>

<body>
   <style type="text/css">
      body {
         background-color: green;
      }

      h1 {
         text-align: center;
      }

      table {
         margin: auto;
         border-style: hidden;
         border-collapse: collapse;
         background-color: gray;
         border-radius: 1rem;
      }

      td {
         width: 20%;
         border: 1px solid black;
         text-align: center;
      }
   </style>
   <img src="bg2.png" alt="Background" width="100px" height="100px">
   <h1>Выручка и премия кассиров "Пятерочки"</h1>
   <?php
   $workers = ['Валентина' => 0.92, 'Тамара' => 1.02, 'Александра' => 1.6, 'Антон' => 1.3, 'Мария' => 0.651];
   $table = "
 <table>
   <tr>
      <td>№</td>
      <td>Имя кассира</td>
      <td>Месячная выручка, млн. руб</td>
      <td>Премия, руб</td>
   </tr>";
   $i = 0;
   foreach ($workers as $key => $value) {
      $i++;
      $salary = $value > 1 ? $value * 0.005 * 1000000 : 0;
      $table .= "<tr>
   <td>{$i}</td>
   <td>{$key}</td>
   <td>{$value}</td>
   <td>{$salary}</td>
 </tr>";
   }
   echo $table . "</table>";
   ?>
</body>

</html>