<?php
include 'conn.php'; // Incluir el archivo de conexión
include 'script.php'; // Incluir el archivo de funciones php

if (isset($_GET['DateStart']) && !empty($_GET['DateStart']) && isset($_GET['DateEnd']) && !empty($_GET['DateEnd'])) {
    $date1 = date_create($_GET['DateStart']);
    $date2 = date_create($_GET['DateEnd']);
}

$threshold = 25; // Umbral del 50% para generar alertas
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Librería generar gráfico radar -->
    <title>Dashboard</title>
</head>
<body>
    <div class="container">
        <!-- Título -->
        <header class="header">
            <h1 class="title">Dashboard</h1>
        </header>
        <!-- Menú -->
        <nav class="nav">
            <ul class="menu">
                <li class="menu-item">
                    <a href="index.php?date=2023-02-01" class="menu-link">Histórico</a>
                </li>
                <li class="menu-item-active">
                    <a href="alerts.php?DateStart=2023-02-01&DateEnd=2023-03-31" class="menu-link">Alertas</a>
                </li>
            </ul>
        </nav>
        <!-- Desplegable elegir día -->
        <section class="form-section">
            <form action="alerts.php" method="get" class="date-form">
                <label for="DateStart" class="form-label">Fecha de Inicio:</label>
                <input type="date" id="DateStart" name="DateStart" class="date-input" value="<?php echo $date1->format('Y-m-d'); ?>" required>
                <br>
                <label for="DateEnd" class="form-label" style="margin-left: 4px">Fecha de Fin:</label>
                <input type="date" id="DateEnd" name="DateEnd" class="date-input" value="<?php echo $date2->format('Y-m-d'); ?>" required>
                <br>
                <button type="submit" class="submit-button">Buscar</button>
            </form>
        </section>
        <?php if (isset($date1) && isset($date2)) : ?>
            <!-- Consulta la entropía en el tiempo -->
            <?php
            $sql_px_total = "Select * from probabilidades";
            $result_px_total = $conn->query($sql_px_total);
            while($row_px_total = $result_px_total->fetch_assoc()) {
                $px_total[] = $row_px_total['px'];
            }
            $entropy_total = calcEntropy($px_total);
            ?>
            <!-- Tabla de alertas -->
            <section class="alerts-box">
                <table style="text-align: center">
                    <tr>
                        <th> Día </th>
                        <th> Dimensión </th>
                        <th> % Desviación </th>
                    </tr>
                    <?php
                    for ($date = $date1; $date <= $date2; $date->modify('+1 day')) {
                        $dateStr = $date->format('Y-m-d');
                        $sql_events_date = "SELECT 'Dimensión: Sistemas' AS type, systems.count FROM systems WHERE systems.date='$dateStr' UNION
                                            SELECT 'Dimensión: Comunicaciones', communications.count FROM communications WHERE communications.date='$dateStr' UNION
                                            SELECT 'Dimensión: Accesos y Usuarios', accessandusers.count AS usuariosyaccesos FROM accessandusers WHERE accessandusers.date='$dateStr' UNION
                                            SELECT 'Dimensión: Aplicaciones', applications.count FROM applications WHERE applications.date='$dateStr' UNION
                                            SELECT 'Dimensión: Malware', malware.count FROM malware WHERE malware.date='$dateStr' UNION
                                            SELECT 'Total de eventos', totals.count FROM totals WHERE totals.date='$dateStr';";
                        $result_events_date = $conn->query($sql_events_date);
                        $events_date = array();
                        while($row_events_date = $result_events_date->fetch_assoc()) {
                            $events_date[] = $row_events_date['count']; // Guardamos en un array los eventos
                        }

                        // Cálculo de las entropías del día seleccionado
                        $date_events_environment = array_pop($events_date);
                        $px_date = array_map(function ($dato) use ($date_events_environment) {
                            return $dato / $date_events_environment;
                        }, $events_date);

                        // Le pasamos las probabilidades a la función que calcula la entropía
                        $entropy_date = calcEntropy($px_date);

                        // Compara la entropía y genera la alerta si es necesario
                        for ($i = 0; $i < count($entropy_date); $i++) {
                            $percentage = abs($entropy_date[$i] * 100 / $entropy_total[$i]);
  
                             switch ($i) {
                                case 0:
                                    $dimension = "Sistemas";
                                    break;
                                case 1:
                                    $dimension = "Comunicaciones";
                                    break;
                                case 2:
                                    $dimension = "Acceso y Usuarios";
                                    break;
                                case 3:
                                    $dimension = "Aplicaciones";
                                    break;
                                case 4:
                                    $dimension = "Detección de malware";
                                    break;
                            }

                            if ($percentage < $threshold OR $percentage > ($threshold + 100)) {
                            ?>  <tr style="cursor: pointer"; onclick="window.location='index.php?date=<?php echo $dateStr ?>'">
                        <?php   echo "<td>$dateStr</td>";
                                echo "<td>$dimension</td>";
                                echo "<td>$percentage</td>";
                                echo "</tr>";

                            }
                        }
                    }
                    ?>
                </table>
            </section>
        <?php endif; ?>
        <?php $conn->close(); ?>
    </div>
</body>
</html>
