@echo off
chcp 65001 >nul
title EcoGuia - Iniciar projeto
echo.
echo ========================================
echo   ECOGUIA - Atividade Somativa 02
echo   Matheus Rodrigues
echo ========================================
echo.

where docker >nul 2>&1
if %errorlevel% neq 0 (
    echo [ERRO] Docker nao encontrado.
    echo Instale o Docker Desktop: https://www.docker.com/products/docker-desktop/
    echo.
    echo Alternativa: use XAMPP e siga o README.txt
    pause
    exit /b 1
)

echo Subindo Apache + PHP + MySQL...
docker compose up -d --build

if %errorlevel% neq 0 (
    echo.
    echo [ERRO] Falha ao iniciar. Verifique se o Docker Desktop esta aberto.
    pause
    exit /b 1
)

echo.
echo Aguarde alguns segundos para o MySQL importar o banco...
timeout /t 8 /nobreak >nul

echo.
echo ========================================
echo   PROJETO RODANDO!
echo ========================================
echo.
echo   Pagina inicial:  http://localhost:8090/index.html
echo   Login:           http://localhost:8090/auth/login.php
echo   phpMyAdmin:      http://localhost:8091  (root / root)
echo.
echo   Usuario demo:
echo     E-mail: admin@ecoguia.com
echo     Senha:  password
echo.
echo   Para parar: docker compose down
echo ========================================
echo.

start http://localhost:8090/index.html
pause
