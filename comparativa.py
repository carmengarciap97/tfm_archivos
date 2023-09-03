import multiprocessing
import subprocess

def run_script1():
    script1_path = "./entropy_base.py"
    subprocess.run(["python", script1_path])

def run_script2():
    script2_path = "./entropy.py"
    subprocess.run(["python", script2_path])

if __name__ == "__main__":
    process1 = multiprocessing.Process(target=run_script1)
    process2 = multiprocessing.Process(target=run_script2)

    process1.start()
    process2.start()

    process1.join()
    process2.join()

    print("Monstrando resultados...")

