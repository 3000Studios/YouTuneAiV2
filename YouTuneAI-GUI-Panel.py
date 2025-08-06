
import tkinter as tk
from tkinter import messagebox
import subprocess
import threading
import os

GENESIS_PATH = r"C:\YouTuneAiV2\YouTuneAI-Genesis\automation"

def run_blackvault():
    subprocess.Popen(["python", os.path.join(GENESIS_PATH, "blackvault_ai_core.py")])

def run_funnel():
    subprocess.Popen(["python", os.path.join(GENESIS_PATH, "auto_funnel.py")])

def launch_gui():
    root = tk.Tk()
    root.title("Genesis AI Launcher")
    root.geometry("400x300")
    root.configure(bg="black")

    title = tk.Label(root, text="YouTuneAI Genesis Control", fg="#39ff14", bg="black", font=("Courier", 18, "bold"))
    title.pack(pady=20)

    start_watchdog = tk.Button(root, text="🛡 Run AI Watchdog", command=run_blackvault, bg="#444", fg="white", font=("Arial", 12, "bold"))
    start_watchdog.pack(pady=10)

    start_moneybot = tk.Button(root, text="💰 Run Auto-Funnel", command=run_funnel, bg="#444", fg="white", font=("Arial", 12, "bold"))
    start_moneybot.pack(pady=10)

    quit_btn = tk.Button(root, text="❌ Quit", command=root.quit, bg="#222", fg="red", font=("Arial", 10))
    quit_btn.pack(pady=20)

    root.mainloop()

if __name__ == '__main__':
    threading.Thread(target=launch_gui).start()
