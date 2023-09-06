import mysql.connector
import math
import matplotlib.pyplot as plt
from collections import defaultdict
import mplcursors

# Función para generar una gráfica de entropía para una dimensión.
def generar_grafica(tabla):
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

        # Realizar una consulta SQL para obtener todos los tipos de eventos
        cursor = conn.cursor()
        cursor.execute(f'SELECT DISTINCT type FROM {tabla};')
        tipos_eventos = [row[0] for row in cursor.fetchall()]
        tipos_eventos.sort()  # Ordenar los tipos de eventos alfabéticamente (esto es para que siempre tenga el mismo orden en la leyenda de la gráfica y sea más facil hacer comparaciones

        # Realizar una consulta SQL para obtener todos los datos
        cursor.execute(f'SELECT time, type, count FROM {tabla} where date(time) > "2023-03-05";')  # Consulta los datos de los 5 primeros días.

        # Obtener los resultados de la consulta
        resultados = cursor.fetchall()

        # Cerrar el cursor y la conexión a la base de datos
        cursor.close()
        conn.close()

        # Crear un diccionario para almacenar los totales de eventos por minuto
        totales_por_minuto = defaultdict(int)

        for row in resultados:
            time, tipo, count = row
            totales_por_minuto[time] += count

        # Crear un diccionario para almacenar los datos de entropía por tipo de evento
        entropia_por_tipo = {tipo: [] for tipo in tipos_eventos}

        for row in resultados:
            time, tipo, count = row
            total_eventos_minuto = totales_por_minuto[time]

            # Calcular la probabilidad de este tipo de evento en ese minuto
            probabilidad_eventos = count / total_eventos_minuto

            # Verificar que la probabilidad esté dentro del rango válido
            if 0 < probabilidad_eventos < 1:
                # Calcular la entropía
                entropia = -total_eventos_minuto * probabilidad_eventos * math.log2(probabilidad_eventos)
                entropia_por_tipo[tipo].append((time, entropia))

        # Crear una única gráfica de líneas con todos los datos
        fig, ax = plt.subplots(figsize=(12, 6))
        lines = {}

        for tipo, datos_entropia in entropia_por_tipo.items():
            times, entropias = zip(*datos_entropia)
            lines[tipo], = ax.plot(times, entropias, label=tipo)

        # Configuración de la gráfica
        ax.set_xlabel('Fecha/Hora')
        ax.set_ylabel('Entropía')
        ax.set_title(f'Entropía para la dimensión: {tabla}')
        ax.legend()
        ax.grid()

        # Habilitar la interacción con el cursor en la gráfica
        mplcursors.cursor(hover=True)

        # Mostrar la gráfica
        plt.show()

    except mysql.connector.Error as err:
        print(f"Error de MySQL: {err}")


# Llamar a la función para generar gráficas para cada tabla
generar_grafica("sistemas")
generar_grafica("comunicaciones")
generar_grafica("accesosyusuarios")
generar_grafica("aplicaciones")
