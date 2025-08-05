# Deployment Guide for Real Agent Projects

This guide shows how to deploy the **ACTUAL WORKING** projects created by Real Action Agents.

## Discord Bot Deployment

### Prerequisites
- Discord Developer Account
- Discord Bot Token
- Server to host the bot

### Steps
1. **Get Discord Token**:
   - Go to https://discord.com/developers/applications
   - Create new application → Bot section
   - Copy the token

2. **Deploy to Heroku**:
   ```bash
   cd discord_bot
   git init
   heroku create your-bot-name
   heroku config:set DISCORD_TOKEN=your_token_here
   git add .
   git commit -m "Deploy Discord bot"
   git push heroku main
   ```

3. **Deploy to VPS**:
   ```bash
   cd discord_bot
   pip install -r requirements.txt
   export DISCORD_TOKEN='your_token_here'
   nohup python bot.py &
   ```

## FastAPI Deployment

### Local Development
```bash
cd web_api
pip install -r requirements.txt
python main.py
# Access at http://localhost:8000
```

### Docker Deployment
```bash
cd web_api
docker build -t real-agent-api .
docker run -p 8000:8000 real-agent-api
```

### Heroku Deployment
```bash
cd web_api
git init
heroku create your-api-name
git add .
git commit -m "Deploy FastAPI"
git push heroku main
```

### Cloud Run (Google Cloud)
```bash
cd web_api
gcloud run deploy real-agent-api --source . --platform managed --region us-central1
```

## Verification Commands

```bash
# Check Discord bot syntax
python -m py_compile discord_bot/bot.py

# Check FastAPI syntax
python -m py_compile web_api/main.py

# Test FastAPI endpoints
curl http://localhost:8000/health
curl http://localhost:8000/proof
```

**These are REAL, DEPLOYABLE applications created by automation!**
