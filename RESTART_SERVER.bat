@echo off
echo ========================================
echo   RESTARTING PHP DEVELOPMENT SERVER
echo ========================================
echo.
echo Stopping any running PHP servers on port 8080...
echo.

REM Kill any PHP processes using port 8080
for /f "tokens=5" %%a in ('netstat -ano ^| findstr :8080 ^| findstr LISTENING') do (
    echo Stopping process %%a...
    taskkill /F /PID %%a 2>nul
)

echo.
echo Waiting 2 seconds...
timeout /t 2 /nobreak >nul

echo.
echo Starting PHP development server with correct timezone...
echo.
echo Server will start at: http://localhost:8080
echo.
echo Press Ctrl+C to stop the server
echo ========================================
echo.

php spark serve

pause
