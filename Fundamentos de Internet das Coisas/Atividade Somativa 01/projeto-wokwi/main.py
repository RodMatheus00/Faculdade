"""
Fundamentos de Internet das Coisas - Atividade Somativa 1
Etapa 3 - Leitura de temperatura e umidade com ESP32 e sensor DHT
Modalidade B - Simulador Wokwi (MicroPython)

Observacao: o simulador Wokwi disponibiliza apenas o componente DHT22.
O driver `dht` do MicroPython atende os dois modelos da familia; para o
kit fisico com DHT11 basta alterar MODELO para "DHT11".
"""

import time
import dht
from machine import Pin

MODELO = "DHT22"
PINO_DADOS = 15
INTERVALO_S = 2
TOTAL_LEITURAS = 10

pino = Pin(PINO_DADOS)
sensor = dht.DHT11(pino) if MODELO == "DHT11" else dht.DHT22(pino)


def ler_sensor():
    """Faz uma medicao e devolve (temperatura_c, umidade_pct)."""
    sensor.measure()
    return sensor.temperature(), sensor.humidity()


def classificar(temperatura, umidade):
    if temperatura > 30 or umidade > 70:
        return "ALERTA"
    if temperatura < 15 or umidade < 30:
        return "ATENCAO"
    return "NORMAL"


print("=" * 68)
print(" ESP32 + sensor {} - Monitoramento de Temperatura e Umidade".format(MODELO))
print(" Pino de dados: GPIO {} | Intervalo: {} s".format(PINO_DADOS, INTERVALO_S))
print("=" * 68)

leitura = 1
while leitura <= TOTAL_LEITURAS:
    try:
        temperatura, umidade = ler_sensor()
        estado = classificar(temperatura, umidade)
        print(
            "Leitura {:02d} | Temperatura: {:>5.1f} C | Umidade: {:>5.1f} % | Status: {}".format(
                leitura, temperatura, umidade, estado
            )
        )
    except OSError as erro:
        print("Leitura {:02d} | Falha na leitura do sensor: {}".format(leitura, erro))

    leitura += 1
    time.sleep(INTERVALO_S)

print("=" * 68)
print("Monitoramento encerrado apos {} leituras.".format(TOTAL_LEITURAS))
