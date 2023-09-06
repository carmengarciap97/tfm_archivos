import mysql.connector
import math
import matplotlib.pyplot as plt
from collections import defaultdict
import mplcursors
from decimal import Decimal  # Importar Decimal

# Función para calcular la entropía total para una tabla
def calcular_entropia_total(tabla, color):
    # Establecer la conexión a la base de datos MySQL
    try:
        conn = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="eventsdata"  # Reemplaza con el nombre de tu base de datos
        )

        if conn.is_connected():
            print(f"Conexión a la base de datos {tabla} exitosa")

        # Realizar una consulta SQL para obtener todos los datos
        cursor = conn.cursor()
        cursor.execute(f'SELECT time, SUM(count) FROM {tabla} WHERE date(time) <= "2023-03-05" GROUP BY time;')
        
        # Obtener los resultados de la consulta
        resultados = cursor.fetchall()

        # Cerrar el cursor y la conexión a la base de datos
        cursor.close()
        conn.close()

        # Crear una lista para almacenar los datos de tiempo y entropía
        times = []
        entropias = []

        for row in resultados:
            time, count = row
            times.append(time)
            total_eventos_minuto = sum(count for _, count in resultados)
            
            # Calcular la probabilidad de eventos en ese minuto
            probabilidad_eventos = Decimal(count) / Decimal(total_eventos_minuto)  # Convertir a Decimal
            
            # Verificar que la probabilidad esté dentro del rango válido
            if 0 < probabilidad_eventos < 1:
                # Calcular la entropía
                entropia = -total_eventos_minuto * probabilidad_eventos * Decimal(math.log2(probabilidad_eventos))  # Convertir a Decimal
                entropias.append(entropia)
            else:
                entropias.append(Decimal(0))  # Convertir a Decimal

        # Agregar una única línea a la gráfica con un color específico
        plt.plot(times, entropias, label=tabla, color=color)

    except mysql.connector.Error as err:
        print(f"Error de MySQL: {err}")


# Llamar a la función para calcular la entropía total para cada tabla
calcular_entropia_total("sistemas", color="blue")
calcular_entropia_total("comunicaciones", color="green")
calcular_entropia_total("accesosyusuarios", color="red")
calcular_entropia_total("aplicaciones", color="purple")

# Configuración de la gráfica
plt.xlabel('Fecha/Hora')
plt.ylabel('Entropía Total')
plt.title('Entropía de referencia del entorno')
plt.legend()
plt.grid()

# Habilitar la interacción con el cursor en la gráfica
mplcursors.cursor(hover=True)

# Mostrar la gráfica
plt.show()
