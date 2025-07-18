@echo off
cd d %~dp0
call venvScriptsactivate.bat
python generate_image.py
pause
