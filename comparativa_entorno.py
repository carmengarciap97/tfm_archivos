import multiprocessing
import subprocess

#Ejecuta el script para generar las gráficas de referencia
def run_script1():
    script1_path = "./entropy_entorno_base.py"
    subprocess.run(["python", script1_path])

#Ejecuta el script para generar las gráficas a observar
def run_script2():
    script2_path = "./entropy_entorno.py"
    subprocess.run(["python", script2_path])

if __name__ == "__main__":
    process1 = multiprocessing.Process(target=run_script1)
    process2 = multiprocessing.Process(target=run_script2)

    process1.start()
    process2.start()

    process1.join()
    process2.join()

    print("Monstrando resultados...")

