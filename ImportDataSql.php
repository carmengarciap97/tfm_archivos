<?php

// Conexión a la base de datos
$mysqli = new mysqli("localhost", "root", "", "eventsdata");

  	
// Carga la biblioteca PhpSpreadsheet, para recorrer el archivo xlsx
require 'vendor/autoload.php'; 
use PhpOffice\PhpSpreadsheet\IOFactory;

set_time_limit(3600); //Establece un límite de tiempo de una hora, ya que son muchos registros y tardará unos minutos


$archivoXLSX = "./DATA.xlsx"; //Ruta archivo xlsx con los eventos totales


// Import eventos de SISTEMAS
$hojaXLSX = 'SISTEMAS'; 
$spreadsheet = IOFactory::load($archivoXLSX);
$worksheet = $spreadsheet->getSheetByName($hojaXLSX);
$highestRow = $worksheet->getHighestRow();


// Recorre la hoja de calculo fila por fila desde la segunda ya que la primera es el título de la columna
for ($row = 2; $row <= $highestRow; ++$row) {
    //Guardamos y formateamos la columna correspondiente a la fecha/hora
    $timeExcel = $worksheet->getCell('A' . $row)->getValue();
    $time = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($timeExcel)->format('Y-m-d H:i:s');

    //Guardamos el total de eventos de cada tipo en su correspondiente variable
    $count1 = $worksheet->getCell('B' . $row)->getValue(); 
    $count2 = $worksheet->getCell('C' . $row)->getValue(); 
    $count3 = $worksheet->getCell('D' . $row)->getValue(); 
    $count4 = $worksheet->getCell('E' . $row)->getValue(); 
    $count5 = $worksheet->getCell('F' . $row)->getValue(); 
    $count6 = $worksheet->getCell('G' . $row)->getValue(); 

    // Insertamos los datos en la tabla
      $sql1 = "INSERT INTO sistemas (time, type, count) VALUES ('$time', 'SYSTEM', $count1)";
      $mysqli->query($sql1);

      $sql2 = "INSERT INTO sistemas (time, type, count) VALUES ('$time', 'PROCESS', $count2)";
      $mysqli->query($sql2);

      $sql3 = "INSERT INTO sistemas (time, type, count) VALUES ('$time', 'SERVICE', $count3)";
      $mysqli->query($sql3);

      $sql4 = "INSERT INTO sistemas (time, type, count) VALUES ('$time', 'WINDOWS', $count4)";
      $mysqli->query($sql4);

      $sql5 = "INSERT INTO sistemas (time, type, count) VALUES ('$time', 'RESOURCE', $count5)";
      $mysqli->query($sql5);

      $sql6 = "INSERT INTO sistemas (time, type, count) VALUES ('$time', 'ADMIN', $count6)";
      $mysqli->query($sql6);

}


// Import eventos de COMUNICACIONES
$hojaXLSX = 'COMUNICACIONES'; 
$spreadsheet = IOFactory::load($archivoXLSX);
$worksheet = $spreadsheet->getSheetByName($hojaXLSX);
$highestRow = $worksheet->getHighestRow();


// Recorre la hoja de calculo fila por fila desde la segunda ya que la primera es el título de la columna
for ($row = 2; $row <= $highestRow; ++$row) {
    //Guardamos y formateamos la columna correspondiente a la fecha/hora
    $timeExcel = $worksheet->getCell('A' . $row)->getValue();
    $time = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($timeExcel)->format('Y-m-d H:i:s');

    //Guardamos el total de eventos de cada tipo en su correspondiente variable
    $count1 = $worksheet->getCell('B' . $row)->getValue(); 
    $count2 = $worksheet->getCell('C' . $row)->getValue(); 
    $count3 = $worksheet->getCell('D' . $row)->getValue(); 
    $count4 = $worksheet->getCell('E' . $row)->getValue(); 
    $count5 = $worksheet->getCell('F' . $row)->getValue(); 
    $count6 = $worksheet->getCell('G' . $row)->getValue(); 

    // Insertamos los datos en la tabla
      $sql1 = "INSERT INTO comunicaciones (time, type, count) VALUES ('$time', 'NETWORK', $count1)";
      $mysqli->query($sql1);

      $sql2 = "INSERT INTO comunicaciones (time, type, count) VALUES ('$time', 'IP', $count2)";
      $mysqli->query($sql2);

      $sql3 = "INSERT INTO comunicaciones (time, type, count) VALUES ('$time', 'DNS', $count3)";
      $mysqli->query($sql3);

      $sql4 = "INSERT INTO comunicaciones (time, type, count) VALUES ('$time', 'DHCP', $count4)";
      $mysqli->query($sql4);

      $sql5 = "INSERT INTO comunicaciones (time, type, count) VALUES ('$time', 'VPN', $count5)";
      $mysqli->query($sql5);

      $sql6 = "INSERT INTO comunicaciones (time, type, count) VALUES ('$time', 'CONNECTION', $count6)";
      $mysqli->query($sql6);

}


// Import eventos de ACCESOS y USUARIOS
$hojaXLSX = 'ACCESOSUSUARIOS'; 
$spreadsheet = IOFactory::load($archivoXLSX);
$worksheet = $spreadsheet->getSheetByName($hojaXLSX);
$highestRow = $worksheet->getHighestRow();


// Recorre la hoja de calculo fila por fila desde la segunda ya que la primera es el título de la columna
for ($row = 2; $row <= $highestRow; ++$row) {
    //Guardamos y formateamos la columna correspondiente a la fecha/hora
    $timeExcel = $worksheet->getCell('A' . $row)->getValue();
    $time = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($timeExcel)->format('Y-m-d H:i:s');

    //Guardamos el total de eventos de cada tipo en su correspondiente variable
    $count1 = $worksheet->getCell('B' . $row)->getValue(); 
    $count2 = $worksheet->getCell('C' . $row)->getValue(); 
    $count3 = $worksheet->getCell('D' . $row)->getValue(); 
    $count4 = $worksheet->getCell('E' . $row)->getValue(); 
    $count5 = $worksheet->getCell('F' . $row)->getValue(); 
    $count6 = $worksheet->getCell('G' . $row)->getValue(); 

    // Insertamos los datos en la tabla
      $sql1 = "INSERT INTO accesosyusuarios (time, type, count) VALUES ('$time', 'USER', $count1)";
      $mysqli->query($sql1);

      $sql2 = "INSERT INTO accesosyusuarios (time, type, count) VALUES ('$time', 'LOGIN or LOGOUT', $count2)";
      $mysqli->query($sql2);

      $sql3 = "INSERT INTO accesosyusuarios (time, type, count) VALUES ('$time', 'ACCESS', $count3)";
      $mysqli->query($sql3);

      $sql4 = "INSERT INTO accesosyusuarios (time, type, count) VALUES ('$time', 'AUTHENTICATION', $count4)";
      $mysqli->query($sql4);

      $sql5 = "INSERT INTO accesosyusuarios (time, type, count) VALUES ('$time', 'PRIVILEGE', $count5)";
      $mysqli->query($sql5);

      $sql6 = "INSERT INTO accesosyusuarios (time, type, count) VALUES ('$time', 'GROUP', $count6)";
      $mysqli->query($sql6);

}


// Import eventos de APLICACIONES
$hojaXLSX = 'APLICACIONES'; 
$spreadsheet = IOFactory::load($archivoXLSX);
$worksheet = $spreadsheet->getSheetByName($hojaXLSX);
$highestRow = $worksheet->getHighestRow();


// Recorre la hoja de calculo fila por fila desde la segunda ya que la primera es el título de la columna
for ($row = 2; $row <= $highestRow; ++$row) {
    //Guardamos y formateamos la columna correspondiente a la fecha/hora
    $timeExcel = $worksheet->getCell('A' . $row)->getValue();
    $time = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($timeExcel)->format('Y-m-d H:i:s');

    //Guardamos el total de eventos de cada tipo en su correspondiente variable
    $count1 = $worksheet->getCell('B' . $row)->getValue(); 
    $count2 = $worksheet->getCell('C' . $row)->getValue(); 
    $count3 = $worksheet->getCell('D' . $row)->getValue(); 
    $count4 = $worksheet->getCell('E' . $row)->getValue(); 
    $count5 = $worksheet->getCell('F' . $row)->getValue(); 
    $count6 = $worksheet->getCell('G' . $row)->getValue(); 

    // Insertamos los datos en la tabla
      $sql1 = "INSERT INTO aplicaciones (time, type, count) VALUES ('$time', 'SQL', $count1)";
      $mysqli->query($sql1);

      $sql2 = "INSERT INTO aplicaciones (time, type, count) VALUES ('$time', 'APACHE', $count2)";
      $mysqli->query($sql2);

      $sql3 = "INSERT INTO aplicaciones (time, type, count) VALUES ('$time', 'CLOUD', $count3)";
      $mysqli->query($sql3);

      $sql4 = "INSERT INTO aplicaciones (time, type, count) VALUES ('$time', 'REQUEST', $count4)";
      $mysqli->query($sql4);

      $sql5 = "INSERT INTO aplicaciones (time, type, count) VALUES ('$time', 'PLUGIN', $count5)";
      $mysqli->query($sql5);

      $sql6 = "INSERT INTO aplicaciones (time, type, count) VALUES ('$time', 'APP', $count6)";
      $mysqli->query($sql6);

}



// Cierra el archivo XLSX y la conexión con la db
$spreadsheet->disconnectWorksheets();
$mysqli->close();


?>

