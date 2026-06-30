@echo off
chcp 65001 >nul
title EcoGuia - Parar projeto
echo Parando containers do EcoGuia...
docker compose down
echo.
echo Projeto parado.
pause
