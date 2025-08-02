<?php
/*
Template Name: YouTuneAI Admin Dashboard
*/

// Check if user is admin
if (!current_user_can('manage_options')) {
    wp_redirect(home_url());
    exit;
}

get_header(); ?>

<div class="admin-dashboard">
    <div class="dashboard-header">
        <h1>YouTuneAI Admin Dashboard</h1>
        <p>Voice-Controlled AI Website Management</p>
    </div>
    
    <div class="voice-control-panel">
        <div class="voice-status" id="voiceStatus">
            <div class="status-indicator" id="statusIndicator"></div>
            <span id="statusText">Voice Control Ready</span>
        </div>
        
        <button id="voiceControlBtn" class="voice-btn">
            🎤 Start Voice Control
        </button>
        
        <div class="command-display" id="commandDisplay">
            <h3>Last Command:</h3>
            <p id="lastCommand">No commands yet</p>
        </div>
    </div>
    
    <div class="dashboard-grid">
        <div class="dashboard-card">
            <h3>🛒 Products</h3>
            <p>Manage WooCommerce products</p>
            <button onclick="executeVoiceCommand('show products')">View Products</button>
        </div>
        
        <div class="dashboard-card">
            <h3>🎨 Theme</h3>
            <p>Customize website appearance</p>
            <button onclick="executeVoiceCommand('change theme colors')">Customize Theme</button>
        </div>
        
        <div class="dashboard-card">
            <h3>📺 Videos</h3>
            <p>Manage background videos</p>
            <button onclick="executeVoiceCommand('change background video')">Change Video</button>
        </div>
        
        <div class="dashboard-card">
            <h3>📊 Analytics</h3>
            <p>View site statistics</p>
            <button onclick="executeVoiceCommand('show analytics')">View Stats</button>
        </div>
    </div>
    
    <div class="command-log">
        <h3>Command History</h3>
        <div id="commandHistory">
            <!-- Command history will be populated here -->
        </div>
    </div>
</div>

<style>
.admin-dashboard {
    max-width: 1200px;
    margin: 40px auto;
    padding: 20px;
    font-family: 'Arial', sans-serif;
}

.dashboard-header {
    text-align: center;
    margin-bottom: 40px;
}

.dashboard-header h1 {
    color: #2c3e50;
    font-size: 2.5em;
    margin-bottom: 10px;
}

.voice-control-panel {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 40px;
    text-align: center;
    color: white;
}

.voice-status {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}

.status-indicator {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #27ae60;
    margin-right: 10px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.1); opacity: 0.7; }
    100% { transform: scale(1); opacity: 1; }
}

.voice-btn {
    background: #e74c3c;
    color: white;
    border: none;
    padding: 15px 30px;
    font-size: 1.2em;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s;
}

.voice-btn:hover {
    background: #c0392b;
    transform: translateY(-2px);
}

.command-display {
    margin-top: 20px;
    padding: 15px;
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.dashboard-card {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s;
}

.dashboard-card:hover {
    transform: translateY(-5px);
}

.dashboard-card h3 {
    color: #2c3e50;
    margin-bottom: 10px;
}

.dashboard-card button {
    background: #3498db;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
}

.command-log {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
}

#commandHistory {
    max-height: 200px;
    overflow-y: auto;
    margin-top: 10px;
}
</style>

<script>
let recognition;
let isListening = false;

// Initialize speech recognition
if ('webkitSpeechRecognition' in window) {
    recognition = new webkitSpeechRecognition();
    recognition.continuous = true;
    recognition.interimResults = true;
    recognition.lang = 'en-US';
    
    recognition.onstart = function() {
        isListening = true;
        document.getElementById('statusText').textContent = 'Listening...';
        document.getElementById('statusIndicator').style.background = '#e74c3c';
        document.getElementById('voiceControlBtn').textContent = '🛑 Stop Listening';
    };
    
    recognition.onresult = function(event) {
        let final_transcript = '';
        for (let i = event.resultIndex; i < event.results.length; i++) {
            if (event.results[i].isFinal) {
                final_transcript += event.results[i][0].transcript;
            }
        }
        
        if (final_transcript) {
            document.getElementById('lastCommand').textContent = final_transcript;
            executeVoiceCommand(final_transcript);
        }
    };
    
    recognition.onend = function() {
        isListening = false;
        document.getElementById('statusText').textContent = 'Voice Control Ready';
        document.getElementById('statusIndicator').style.background = '#27ae60';
        document.getElementById('voiceControlBtn').textContent = '🎤 Start Voice Control';
    };
}

document.getElementById('voiceControlBtn').addEventListener('click', function() {
    if (isListening) {
        recognition.stop();
    } else {
        recognition.start();
    }
});

function executeVoiceCommand(command) {
    // Add to command history
    const historyDiv = document.getElementById('commandHistory');
    const commandEntry = document.createElement('div');
    commandEntry.textContent = `${new Date().toLocaleTimeString()}: ${command}`;
    commandEntry.style.padding = '5px';
    commandEntry.style.borderBottom = '1px solid #ddd';
    historyDiv.insertBefore(commandEntry, historyDiv.firstChild);
    
    // Send command to AI controller
    fetch('/wp-admin/admin-ajax.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=process_ai_command&command=${encodeURIComponent(command)}&nonce=${aiControllerNonce}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Command executed successfully: ' + data.message);
        } else {
            alert('Command failed: ' + data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to execute command');
    });
}
</script>

<?php get_footer(); ?>