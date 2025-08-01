# YouTuneAI - Voice-Controlled AI Website

🎤 **Voice-Controlled WordPress Theme with Live AI Deployment**

A revolutionary WordPress theme that responds to voice commands and updates your website in real-time using AI.

## 🚀 Features

### 🧠 AI-Powered Voice Control
- **Voice Commands**: Speak naturally to update your website
- **GPT-4 Integration**: Advanced natural language processing
- **Real-time Updates**: Changes deploy instantly to your live site
- **Smart Interpretation**: AI understands context and intent

### 🎨 Modern UI/UX
- **Fullscreen Video Backgrounds**: Dynamic, AI-controllable backgrounds
- **Glass Morphism Design**: Modern glassmorphic UI elements
- **Animated Components**: Smooth AOS animations throughout
- **Responsive Design**: Perfect on all devices

### 🛒 E-commerce Ready
- **Digital Products**: Sell avatars, overlays, music, and tools
- **Payment Integration**: PayPal, Stripe, and CashApp support
- **Smart Shopping Cart**: Dynamic cart with local storage
- **Product Management**: Voice-controlled product additions

### 🎮 Streaming Platform
- **Live Streaming**: Twitch integration for live content
- **Interactive Chat**: Real-time chat system
- **Stream Categories**: Gaming, Music, Creative, Education
- **Community Features**: Follow, share, and interact

### 🎵 Audio Experience
- **Background Music**: Corporate-free music rotation
- **Click Sounds**: Satisfying UI interaction sounds
- **Voice Feedback**: Audio confirmations for commands
- **Music Controls**: Voice-controlled audio management

## 🛠 Installation

### Prerequisites
- WordPress 5.0+
- Python 3.8+
- SFTP access to your hosting
- OpenAI API key
- Microphone for voice commands

### Quick Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/yourusername/youtuneai.git
   cd youtuneai
   ```

2. **Install Python dependencies:**
   ```bash
   pip install -r requirements.txt
   ```

3. **Configure environment:**
   ```bash
   cp .env.example .env
   # Edit .env with your API keys and hosting details
   ```

4. **Deploy WordPress theme:**
   ```bash
   # Upload wp-theme-youtuneai folder to /wp-content/themes/
   # Activate theme in WordPress admin
   ```

5. **Start the AI controller:**
   ```bash
   python ai_controller.py
   ```

## 🎤 Voice Commands

### Website Updates
- *"Change background video to space theme"*
- *"Update homepage title to 'Welcome to the Future'"*
- *"Add new product called Gaming Headset for $99"*
- *"Change primary color to blue"*

### Content Management
- *"Create a new blog post about AI"*
- *"Update the streaming page content"*
- *"Add a new menu item for tutorials"*

### E-commerce
- *"Add product: AI Avatar Pack, price $29.99"*
- *"Update shop description"*
- *"Enable PayPal payments"*

### Design Changes
- *"Make the header more transparent"*
- *"Change accent color to purple"*
- *"Update footer links"*

## 📁 Project Structure

```
YouTuneAiV2/
├── wp-theme-youtuneai/          # WordPress theme files
│   ├── style.css                # Main stylesheet
│   ├── functions.php            # Theme functions and AI processing
│   ├── header.php               # Site header
│   ├── footer.php               # Site footer
│   ├── index.php                # Main template
│   ├── page-home.php            # Homepage template
│   ├── page-streaming.php       # Streaming page
│   └── assets/                  # Images, JS, CSS, audio
│       ├── js/main.js           # JavaScript functionality
│       ├── images/              # Theme images
│       ├── video/               # Background videos
│       └── audio/               # Sound effects and music
├── ai_controller.py             # Main AI voice controller
├── requirements.txt             # Python dependencies
├── .env                         # Environment configuration
└── README.md                    # This file
```

## 🔧 Configuration

### OpenAI Setup
1. Get API key from [OpenAI](https://platform.openai.com/api-keys)
2. Add to `.env` file:
   ```
   OPENAI_API_KEY=your_key_here
   ```

### Hosting Setup (IONOS)
1. Configure SFTP credentials in `.env`:
   ```
   SFTP_HOST=your_host
   SFTP_USERNAME=your_username
   SFTP_PASSWORD=your_password
   ```

### Payment Integration
1. **PayPal:**
   ```
   PAYPAL_CLIENT_ID=your_client_id
   PAYPAL_CLIENT_SECRET=your_secret
   ```

2. **Stripe:**
   ```
   STRIPE_PUBLISHABLE_KEY=pk_test_your_key
   STRIPE_SECRET_KEY=sk_test_your_key
   ```

## 🎯 Usage

### Starting the Voice Controller
```bash
python ai_controller.py
```

### Voice Activation
1. Say "Hey YouTune" to activate
2. Speak your command naturally
3. AI processes and executes the command
4. Changes deploy automatically to your live site

### Admin Panel
- Access via voice: *"Open admin panel"*
- Or click the ⚡ Admin button in navigation
- Type or speak commands directly

### Example Workflow
1. **Start Controller**: `python ai_controller.py`
2. **Voice Command**: *"Change background to ocean theme"*
3. **AI Processing**: GPT-4 interprets the command
4. **Execution**: Background video updates
5. **Deployment**: Changes push to live site
6. **Confirmation**: Voice and visual feedback

## 🔐 Security

- API keys stored in environment variables
- SFTP connections encrypted
- WordPress nonce verification
- Input sanitization and validation
- Command logging for audit trail

## 🚀 Advanced Features

### Auto-Deploy
- File watching for automatic deployment
- Git integration for version control
- Backup creation before changes
- Rollback capabilities

### Monitoring
- Command execution logging
- Performance metrics
- Error tracking and reporting
- Usage analytics

### Extensions
- Custom voice commands
- Plugin integration
- Third-party API connections
- Advanced AI models

## 🐛 Troubleshooting

### Voice Recognition Issues
1. Check microphone permissions
2. Verify audio input levels
3. Test with simple commands first
4. Check Python audio dependencies

### Deployment Problems
1. Verify SFTP credentials
2. Check file permissions
3. Confirm network connectivity
4. Review error logs

### AI Processing Errors
1. Validate OpenAI API key
2. Check API quota limits
3. Review command syntax
4. Monitor API responses

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- OpenAI for GPT-4 API
- WordPress community
- CodePen inspirations for UI components
- Voice recognition libraries
- All contributors and testers

## 📞 Support

- **Email**: mr.jwswain@gmail.com
- **Discord**: [YouTuneAI Community](https://discord.gg/youtuneai)
- **Issues**: [GitHub Issues](https://github.com/3000Studios/youtuneai/issues)

---

**Made with ❤️ and AI by Boss Man J**

*"Just say the word — and watch your website transform."*

**⚖️ IMPORTANT LEGAL NOTICE:**
YouTuneAI contains proprietary voice-controlled AI website technology. 
Commercial use requires paid licensing. Contact mr.jwswain@gmail.com
Unauthorized use is subject to legal action and monetary damages.
