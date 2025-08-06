import subprocess

def launch_all_agents():
    agents = [
        'gpt_assistant.py',
        'voice_listener.py',
        'task_auto_pusher.py',
        'auto_commit_bot.py',
        'ai_optimizer_loop.py',
        'whisper_voice_commands.py'
    ]
    for agent in agents:
        try:
            subprocess.Popen(['python', f'blackvault/{agent}'])
        except Exception as e:
            print(f"[!] Failed to launch {agent}: {e}")

if __name__ == '__main__':
    launch_all_agents()