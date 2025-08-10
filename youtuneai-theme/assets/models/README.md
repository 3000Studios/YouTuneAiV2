# YouTuneAI Theme - Models Directory

This directory contains 3D models used by the theme:

## Model Files

- `default-avatar.glb` - Default 3D avatar model
- `garage-parts/` - 3D models for YouTune Garage configurator
- `vr-environments/` - VR room environment models
- `props/` - Additional 3D props and objects

## Supported Formats

- GLB (preferred for web performance)
- GLTF (with separate textures)
- FBX (requires conversion)

## Optimization Guidelines

- Keep models under 5MB for web performance
- Use Draco compression when possible
- Texture resolution: max 2048x2048 for most models
- Use KTX2 format for textures when supported

## Adding New Models

1. Place GLB files in appropriate subdirectory
2. Update model metadata in WordPress admin
3. Test loading performance on mobile devices

For production, all models should be optimized using tools like:
- gltf-pipeline
- Blender's GLB export with compression
- Khronos Group's gltf-validator