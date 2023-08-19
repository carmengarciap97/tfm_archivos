<?php
include 'conn.php'; // Incluir el archivo de conexión
include 'script.php'; // Incluir el archivo de funciones php

if (isset($_GET['date']) && !empty($_GET['date'])) {
    $dateselected = $_GET['date'];
}
?>

<!DOCTYPE html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!– Librería generar gráfico radar –>
    <title>Dashboard</title>
</head>

<body>
    <div class="container">

        <!– Título –>
        <header class="header">
            <h1 class="title">Dashboard</h1>
        </header>

        <!– Menú –>
         <nav class="nav">
            <ul class="menu">
                <li class="menu-item-active">
                    <a href="index.php?date=2023-02-01" class="menu-link">Histórico</a>
                </li>
                <li class="menu-item">
                    <a href="alerts.php?DateStart=2023-02-01&DateEnd=2023-03-31" class="menu-link">Alertas</a>
                </li>
            </ul>
        </nav>

        <!– Desplegable elegir día –>
        <section class="form-section">
            <form action="index.php" method="get" class="date-form">
                <label for="date" class="form-label">Selecciona una fecha:</label>
                <input type="date" id="fecha" name="date" class="date-input" value="<?php echo date_create($dateselected)->format('Y-m-d'); ?>"  required>
                <button type="submit" class="submit-button">Mostrar Datos</button>
            </form>
        </section>

        <!– Resumen eventos del día seleccionado –>
        <?php if (isset($dateselected)) : ?>
        <section class="selected-date-section">
            <div class="selected-date-box">
                Resumen de los eventos del día: <?php echo date_format(date_create($dateselected), 'd/m/Y');
                echo "<br>";

                //Consulta para generar tabla con datos
                $sql_events_date = "SELECT 'Dimensión: Sistemas' AS type, systems.count FROM systems WHERE systems.date='$dateselected' UNION
                                    SELECT 'Dimensión: Comunicaciones', communications.count FROM communications WHERE communications.date='$dateselected' UNION
                                    SELECT 'Dimensión: Accesos y Usuarios', accessandusers.count AS usuariosyaccesos FROM accessandusers WHERE accessandusers.date='$dateselected' UNION
                                    SELECT 'Dimensión: Aplicaciones', applications.count FROM applications WHERE applications.date='$dateselected' UNION
                                    SELECT 'Dimensión: Malware', malware.count FROM malware WHERE malware.date='$dateselected' UNION
                                    SELECT 'Total de eventos', totals.count FROM totals WHERE totals.date='$dateselected';";
                $result_events_date = $conn->query($sql_events_date);
                echo "<table>";
                  while($row_events_date = $result_events_date->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row_events_date["type"] . "</td>";
                    echo "<td>" . $row_events_date["count"] . "</td>";
                    echo "</tr>";
                    $events_date[] = $row_events_date['count'];  //Guardamos en una array los eventos para más adelante calcular la entropía
                  }
                echo "</table>";
                ?>
            </div>

        <!– Caja para colocar el gráfico radar –>
        <div class="radar">
            <canvas id="radarChart" width="400" height="400"></canvas>
        </div>

        </section>

<?php
// Cálculo de las entropías a lo largo del tiempo
    $sql_px_total = "Select * from probabilidades"; //Consulta ya creada en la db para mostrar las probabilidades de cada dimensión
                    $result_px_total = $conn->query($sql_px_total);
                    while($row_px_total = $result_px_total->fetch_assoc()) {
            
                        $px_total[] = $row_px_total['px'];
                      }

    $entropy_total = calcEntropy($px_total);


// Cálculo de las entropías del día seleccionado
    $date_events_environment = array_pop($events_date);  //sacamos del array generado anteriormente el último número que corresponde al total de eventos del entorno

    //Dividimos el total de eventos de cada dimensión por el total de eventos del entorno para conseguir su probabilidad (P(x))
    $px_date_events = array();
        foreach ($events_date as $dato) {
          $px_date[] = $dato / $date_events_environment;
        }

    //Le pasamos las probabilidades a la funcion que calcula la entropía
    $entropy_date = calcEntropy($px_date);


?>
<script>
//Generamos los dos gráficos superpuestos
var total_entropy = <?php echo json_encode($entropy_total); ?>;
var date_entropy = <?php echo json_encode($entropy_date); ?>;

    var ctx = document.getElementById('radarChart').getContext('2d');

    var data = {
        labels: ['Sistemas', 'Accesos y Usuarios', 'Comunicaciones', 'Aplicaciones', 'Malware'],
        datasets: [{
            label: 'Entropía seleccionada' ,
            data: date_entropy,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2
        },
                {
                    label: 'Entropía global',
                    data: total_entropy,
                    backgroundColor: 'rgba(192, 75, 192, 0.2)',
                    borderColor: 'rgba(192, 75, 192, 1)',
                    borderWidth: 2
                }]
    };

    // Opciones del gráfico
    var options = {
        scale: {
            ticks: {
                beginAtZero: true,
                max: 10 // Puedes ajustar este valor según tus datos
            }
        }
    };

    // Crear el gráfico radar
    var radarChart = new Chart(ctx, {
        type: 'radar',
        data: data,
        options: options
    });


</script>

       
        <?php endif; $conn->close(); ?>
    </div>

</body>
</html>


