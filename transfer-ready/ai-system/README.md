# AI System Files

This directory previously contained duplicate copies of the AI controller files.

## Current Location

All AI controller files are now maintained in the canonical location:

```
src/deployment/
├── ai_controller.py
├── enhanced_ai_controller.py
├── working_deployment_controller.py
├── direct_css_upload.py
├── find_wordpress.py
├── server_explorer.py
└── ionos_webspace_analyzer.py
```

## Why This Change?

Duplicate files were removed to:
- Reduce maintenance burden
- Prevent version drift between copies
- Improve code clarity and organization
- Follow single-source-of-truth principle

## Usage

Import or run these files from their canonical location:

```python
from src.deployment.ai_controller import YouTuneAIController
from src.deployment.working_deployment_controller import YouTuneAIDeploymentController
```

Or run directly:

```bash
python src/deployment/ai_controller.py
python src/deployment/working_deployment_controller.py
```
