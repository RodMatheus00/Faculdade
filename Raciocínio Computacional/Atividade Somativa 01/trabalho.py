"""
Gerenciador de Projetos Pessoais
Raciocinio Computacional - Atividade Somativa 1
Autor: Matheus Rodrigues

Aplicacao de linha de comando que le comandos do usuario dentro de um loop
e mantem uma lista de projetos na memoria enquanto o programa esta rodando.

Comandos disponiveis:
    ADD <nome>  adiciona um projeto na lista
    LIST        mostra todos os projetos cadastrados, numerados
    HELP        mostra a lista de comandos
    EXIT        encerra o programa
"""

# A lista precisa ser criada ANTES do while.
# Se ela fosse criada dentro do loop, seria zerada a cada volta e o programa
# perderia todos os projetos ja cadastrados.
projetos = []

print("=" * 55)
print(" GERENCIADOR DE PROJETOS PESSOAIS")
print(" Digite HELP para ver os comandos disponiveis.")
print("=" * 55)

while True:
    entrada = input("\n> ").strip()

    # Ignora quando o usuario aperta Enter sem digitar nada.
    if entrada == "":
        print("Nenhum comando digitado. Use HELP para ver as opcoes.")
        continue

    # Separa a primeira palavra (o comando) do restante (o argumento).
    partes = entrada.split(" ", 1)
    comando = partes[0].upper()

    if comando == "EXIT":
        print("Encerrando o programa. Ate a proxima!")
        break

    elif comando == "HELP":
        print("Comandos disponiveis:")
        print("  ADD <nome>  - adiciona um novo projeto")
        print("  LIST        - lista todos os projetos cadastrados")
        print("  HELP        - mostra esta ajuda")
        print("  EXIT        - encerra o programa")

    elif comando == "ADD":
        # Se o usuario digitou apenas "ADD", nao existe a segunda parte.
        if len(partes) < 2 or partes[1].strip() == "":
            print("Erro: informe o nome do projeto. Exemplo: ADD Estudar Python")
        else:
            nome_do_projeto = partes[1].strip()
            projetos.append(nome_do_projeto)
            print("Projeto '{}' adicionado com sucesso!".format(nome_do_projeto))

    elif comando == "LIST":
        if len(projetos) == 0:
            print("Nao ha nenhum projeto a ser listado no momento.")
        else:
            print("Projetos cadastrados ({}):".format(len(projetos)))
            contador = 1
            for projeto in projetos:
                print("{}. {}".format(contador, projeto))
                contador += 1

    else:
        print("Comando '{}' nao reconhecido. Use HELP para ver as opcoes.".format(comando))
