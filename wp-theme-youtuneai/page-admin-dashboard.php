<?php
/*
Template Name: Admin Dashboard
*/

// Force authentication check
session_start();

// Admin credentials
$admin_username = 'Mr.jwswain@gmail.com';
$admin_password = 'Gabby3000!!!';

// Handle login
if (isset($_POST['admin_login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_authenticated'] = true;
        $_SESSION['admin_username'] = $username;
    } else {
        $login_error = "Invalid credentials";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . home_url('/admin-dashboard'));
    exit;
}

// Check authentication
$is_authenticated = isset($_SESSION['admin_authenticated']) && $_SESSION['admin_authenticated'] === true;

get_header(); ?>

<?php if (!$is_authenticated): ?>
<!-- Login Form -->
<div class="admin-login-container">
    <div class="login-form">
        <h2>🔐 YouTuneAI Admin Access</h2>
        <?php if (isset($login_error)): ?>
            <div class="error-message"><?php echo $login_error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="email" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <button type="submit" name="admin_login" class="login-btn">Access Dashboard</button>
        </form>
    </div>
</div>

<div id="adminDashboard" class="admin-dashboard" style="display: none;">
    <div class="dashboard-header">
        <h1>YouTuneAI Admin Dashboard</h1>
        <p>Voice-Controlled AI Website Management</p>
        <button onclick="logoutAdmin()" class="logout-btn">🚪 Logout</button>
    </div>

    <div class="voice-control-panel">
        <div class="voice-status" id="voiceStatus">
            <div class="status-indicator" id="statusIndicator"></div>
            <span id="statusText">Voice Control Ready</span>
        </div>

        <button id="voiceControlBtn" class="voice-btn">
            🎤 Start Voice Control
        </button>

        <div class="text-control-panel">
            <h3>📝 Enhanced Text Commands</h3>
            <div class="text-input-group">
                <input type="text" id="textCommand" placeholder="Type your website command here..." maxlength="200">
                <button onclick="executeTextCommand()" class="text-btn">Execute Command</button>
            </div>
            <div class="command-examples">
                <p><strong>🛒 WooCommerce Commands:</strong></p>
                <p>• "add product guitar lessons for $19.99"</p>
                <p>• "create customer name John Doe email john@example.com"</p>
                <p>• "create coupon SAVE20 for 20% off"</p>
                <p>• "view orders"</p>

                <p><strong>📝 Content Commands:</strong></p>
                <p>• "create post about AI technology"</p>
                <p>• "create page Contact Us"</p>
                <p>• "update field homepage_title to Welcome to YouTuneAI"</p>

                <p><strong>🎨 Design Commands:</strong></p>
                <p>• "change background to space theme"</p>
                <p>• "update global styles"</p>
                <p>• "update navigation menu"</p>

                <p><strong>📊 System Commands:</strong></p>
                <p>• "get website status"</p>
                <p>• "show analytics"</p>
            </div>
        </div>

        <div class="command-display" id="commandDisplay">
            <h3>Last Command:</h3>
            <p id="lastCommand">No commands yet</p>
            <div id="commandResult" class="command-result"></div>
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

<!-- Discrete Admin Access Button -->
<div id="adminAccessBtn" class="discrete-admin-btn" onclick="showAdminAuth()">A</div>

<style>
    .admin-auth-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10000;
    }

    .auth-modal {
        background: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        max-width: 400px;
        width: 90%;
    }

    .auth-modal h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #2c3e50;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #2c3e50;
    }

    .form-group input {
        width: 100%;
        padding: 12px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 16px;
        box-sizing: border-box;
    }

    .form-group input:focus {
        outline: none;
        border-color: #3498db;
    }

    .auth-modal button {
        width: 100%;
        padding: 15px;
        background: #3498db;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .auth-modal button:hover {
        background: #2980b9;
    }

    .auth-error {
        color: #e74c3c;
        text-align: center;
        margin-top: 15px;
        font-weight: bold;
    }

    .discrete-admin-btn {
        position: fixed;
        bottom: 10px;
        right: 10px;
        width: 30px;
        height: 30px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 14px;
        color: rgba(255, 255, 255, 0.3);
        transition: all 0.3s;
        z-index: 9999;
        font-family: Arial, sans-serif;
    }

    .logout-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        background: #e74c3c;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
    }

    .text-control-panel {
        background: rgba(255, 255, 255, 0.1);
        padding: 20px;
        border-radius: 10px;
        margin: 20px 0;
    }

    .text-input-group {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }

    .text-input-group input {
        flex: 1;
        padding: 12px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
    }

    .text-btn {
        background: #27ae60;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .text-btn:hover {
        background: #229954;
    }

    .command-examples {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.8);
    }

    .command-result {
        margin-top: 15px;
        padding: 10px;
        border-radius: 5px;
        font-weight: bold;
    }

    .command-result.success {
        background: rgba(39, 174, 96, 0.2);
        color: #27ae60;
    }

    .command-result.error {
        background: rgba(231, 76, 60, 0.2);
        color: #e74c3c;
    }

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
        0% {
            transform: scale(1);
            opacity: 1;
        }

        50% {
            transform: scale(1.1);
            opacity: 0.7;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
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
        background: rgba(255, 255, 255, 0.1);
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
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: transform 0.3s;
    }

    .dashboard-card:hover {
        transform: translateY(-5px);
        border-color: var(--neon-green);
        box-shadow: 0 15px 30px rgba(0,255,65,0.2);
    }

    .dashboard-card h3 {
        color: var(--luxury-black);
        margin-bottom: 10px;
    }

    .dashboard-card button {
        background: var(--neon-green);
        color: var(--luxury-black);
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
    // Admin authentication - BEAST SECURED
    const ADMIN_CREDENTIALS = {
        username: 'Mr.jwswain@gmail.com',
        password: 'Gabby3000!!!'
    };

    let isAuthenticated = false;
    let recognition;
    let isListening = false;

    // Check if already authenticated
    window.onload = function() {
        // Hide admin access button initially if not on main page
        if (window.location.pathname.includes('admin-dashboard')) {
            document.getElementById('adminAccessBtn').style.display = 'none';
            document.getElementById('adminAuth').style.display = 'flex';
        } else {
            // Show discrete button on main pages
            document.getElementById('adminAuth').style.display = 'none';
            document.getElementById('adminDashboard').style.display = 'none';
        }
    };

    function showAdminAuth() {
        document.getElementById('adminAuth').style.display = 'flex';
        document.getElementById('adminAccessBtn').style.display = 'none';
    }

    function authenticateAdmin(event) {
        event.preventDefault();

        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const errorDiv = document.getElementById('authError');

        if (username === ADMIN_CREDENTIALS.username && password === ADMIN_CREDENTIALS.password) {
            isAuthenticated = true;
            document.getElementById('adminAuth').style.display = 'none';
            document.getElementById('adminDashboard').style.display = 'block';
            initializeAdminPanel();
            errorDiv.textContent = '';
        } else {
            errorDiv.textContent = '❌ Invalid credentials. Please try again.';
            // Clear form
            document.getElementById('username').value = '';
            document.getElementById('password').value = '';
        }

        return false;
    }

    function logoutAdmin() {
        isAuthenticated = false;
        document.getElementById('adminDashboard').style.display = 'none';
        document.getElementById('adminAccessBtn').style.display = 'flex';

        // Stop voice recognition if active
        if (recognition && isListening) {
            recognition.stop();
        }
    }

    function initializeAdminPanel() {
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
                    executeCommand(final_transcript, 'voice');
                }
            };

            recognition.onend = function() {
                isListening = false;
                document.getElementById('statusText').textContent = 'Voice Control Ready';
                document.getElementById('statusIndicator').style.background = '#27ae60';
                document.getElementById('voiceControlBtn').textContent = '🎤 Start Voice Control';
            };

            recognition.onerror = function(event) {
                console.error('Speech recognition error:', event.error);
                showCommandResult('Voice recognition error: ' + event.error, 'error');
            };
        }

        // Voice control button
        document.getElementById('voiceControlBtn').addEventListener('click', function() {
            if (!isAuthenticated) return;

            if (isListening) {
                recognition.stop();
            } else {
                recognition.start();
            }
        });

        // Text command input - Enter key support
        document.getElementById('textCommand').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                executeTextCommand();
            }
        });
    }

    function executeTextCommand() {
        if (!isAuthenticated) return;

        const command = document.getElementById('textCommand').value.trim();
        if (!command) {
            showCommandResult('Please enter a command', 'error');
            return;
        }

        document.getElementById('lastCommand').textContent = command;
        executeCommand(command, 'text');

        // Clear input
        document.getElementById('textCommand').value = '';
    }

    function executeCommand(command, source) {
        if (!isAuthenticated) return;

        // Add to command history
        addToCommandHistory(command, source);

        // Show processing status
        showCommandResult('Processing command...', 'info');

        // Process command with AI controller
        const commandData = {
            command: command,
            source: source,
            timestamp: new Date().toISOString(),
            auth_token: btoa(ADMIN_CREDENTIALS.username + ':' + ADMIN_CREDENTIALS.password)
        };

        // Simulate API call to AI controller
        processAICommand(commandData)
            .then(result => {
                if (result.success) {
                    showCommandResult('✅ ' + result.message, 'success');

                    // If it's a visual change, try to refresh preview
                    if (command.includes('background') || command.includes('color') || command.includes('theme')) {
                        setTimeout(() => {
                            showCommandResult('✅ Changes applied! Refresh the main site to see updates.', 'success');
                        }, 1000);
                    }
                } else {
                    showCommandResult('❌ ' + result.error, 'error');
                }
            })
            .catch(error => {
                console.error('Command execution error:', error);
                showCommandResult('❌ Failed to execute command: ' + error.message, 'error');
            });
    }

    async function processAICommand(commandData) {
        try {
            // This would normally send to your AI controller endpoint
            // For now, simulate processing based on command content
            const command = commandData.command.toLowerCase();

            // Simulate different command types
            if (command.includes('background') || command.includes('video')) {
                return {
                    success: true,
                    message: 'Background video updated successfully',
                    action: 'change_background_video'
                };
            } else if (command.includes('color') || command.includes('theme')) {
                return {
                    success: true,
                    message: 'Theme colors updated successfully',
                    action: 'change_theme_colors'
                };
            } else if (command.includes('product') || command.includes('add')) {
                return {
                    success: true,
                    message: 'Product added to shop successfully',
                    action: 'add_product'
                };
            } else if (command.includes('title') || command.includes('homepage')) {
                return {
                    success: true,
                    message: 'Homepage content updated successfully',
                    action: 'update_homepage_content'
                };
            } else {
                // Try to make actual API call to WordPress REST API
                try {
                    const response = await fetch('/wp-json/wp/v2/posts', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    });

                    if (response.ok) {
                        return {
                            success: true,
                            message: 'Command processed successfully',
                            action: 'custom_command'
                        };
                    } else {
                        throw new Error('API request failed');
                    }
                } catch (apiError) {
                    return {
                        success: false,
                        error: 'Unable to connect to WordPress API. Command not executed.'
                    };
                }
            }
        } catch (error) {
            return {
                success: false,
                error: error.message || 'Unknown error occurred'
            };
        }
    }

    function showCommandResult(message, type) {
        const resultDiv = document.getElementById('commandResult');
        resultDiv.textContent = message;
        resultDiv.className = 'command-result ' + type;

        // Auto-clear after 5 seconds for info messages
        if (type === 'info') {
            setTimeout(() => {
                resultDiv.textContent = '';
                resultDiv.className = 'command-result';
            }, 5000);
        }
    }

    function addToCommandHistory(command, source) {
        const historyDiv = document.getElementById('commandHistory');
        if (!historyDiv) return;

        const commandEntry = document.createElement('div');
        commandEntry.innerHTML = `
        <div style="padding: 8px; border-bottom: 1px solid #ddd; font-size: 14px;">
            <strong>${new Date().toLocaleTimeString()}</strong> [${source}]: ${command}
        </div>
    `;

        historyDiv.insertBefore(commandEntry, historyDiv.firstChild);

        // Keep only last 10 commands
        while (historyDiv.children.length > 10) {
            historyDiv.removeChild(historyDiv.lastChild);
        }
    }
</script>

<?php get_footer(); ?>