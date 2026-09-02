# Diário de Bordo — Gerenciador de Projetos Pessoais

**Disciplina:** Raciocínio Computacional
**Aluno:** Matheus Rodrigues
**Projeto:** Aplicação de linha de comando para gerenciar projetos pessoais

---

## Sobre o projeto

A ideia é ter um programa simples de terminal onde eu consigo cadastrar os
projetos que quero tocar e consultar essa lista a qualquer momento, sem abrir
nenhum aplicativo pesado. A cada semana da disciplina o programa ganha uma
capacidade nova.

---

## Semana 1 — O esqueleto e o loop

Comecei pelo básico: fazer o programa não morrer depois de uma única
interação. Descobri que sem um laço o script executa de cima para baixo e
encerra, então usei `while True` para manter o programa vivo esperando
comandos.

O ponto que me travou aqui foi perceber que um `while True` sem uma condição de
saída trava o terminal. Precisei do `break` associado a um comando `EXIT` para
ter uma porta de saída.

**Aprendizado:** todo laço infinito precisa de uma saída planejada, senão vira
um bug e não uma funcionalidade.

---

## Semana 2 — Reconhecendo comandos

Nesta etapa fiz o programa entender o que o usuário digita. Usei `input()` para
capturar a entrada e uma cadeia de `if` / `elif` / `else` para decidir o que
fazer.

Duas coisas que resolvi aqui:

- **Espaços sobrando.** Se eu digitasse `" LIST "` o programa não reconhecia.
  Resolvi com `.strip()`, que remove os espaços das pontas.
- **Maiúsculas e minúsculas.** `add` e `ADD` eram tratados como comandos
  diferentes. Usei `.upper()` para normalizar antes de comparar.

Também precisei separar o comando do argumento no caso do `ADD`, porque o nome
do projeto vem depois da primeira palavra. Usei `split(" ", 1)`, que corta a
string apenas na primeira ocorrência do espaço e me devolve duas partes: o
comando e o resto inteiro. Se eu usasse `split()` sem o limite, um projeto com
nome de três palavras seria quebrado em três pedaços.

**Aprendizado:** tratar a entrada do usuário é metade do trabalho. As pessoas
digitam de formas diferentes daquilo que eu imagino.

---

## Semana 3 — Dando memória ao programa (esta entrega)

Este foi o marco do projeto. Até aqui o `ADD` só exibia uma mensagem bonita de
sucesso e esquecia tudo em seguida. Agora ele realmente guarda.

### A decisão mais importante: onde criar a lista

O enunciado sugeriu pensar se a lista deve ser criada antes ou depois do
`while True`. Testei os dois casos e a diferença é gritante.

Se eu escrever `projetos = []` **dentro** do laço, a cada volta a variável é
reatribuída para uma lista vazia. Na prática eu adicionava um projeto, e no
comando seguinte a lista já estava zerada de novo — exatamente a "amnésia" que
eu queria resolver.

Criando `projetos = []` **antes** do laço, a lista é construída uma única vez e
o laço apenas a modifica. Ela sobrevive entre as iterações.

Isso me fez entender na prática o conceito de **escopo e tempo de vida de uma
variável**: não basta a variável existir, ela precisa existir no lugar certo em
relação ao fluxo do programa. Se me perguntarem isso numa entrevista, a
resposta é essa — antes do laço, porque o estado precisa persistir entre as
iterações.

### O comando ADD

A mudança foi pequena no código e grande no efeito: depois de extrair o nome do
projeto, chamo `projetos.append(nome_do_projeto)`. O `append` adiciona o item no
final da lista. A mensagem de sucesso continuou a mesma, como pedia o enunciado.

Aproveitei para tratar o caso de digitar só `ADD` sem nenhum nome. Antes isso
quebrava o programa com um `IndexError`, porque eu tentava acessar `partes[1]` e
essa posição não existia. Agora verifico `len(partes) < 2` antes de acessar.

**Aprendizado:** sempre checar se um índice existe antes de usá-lo. Foi meu
primeiro erro em tempo de execução causado por uma entrada que eu não previ.

### O comando LIST

Criei um novo `elif` para o `LIST`. Ele tem dois caminhos:

1. **Lista vazia.** Verifico com `len(projetos) == 0` e mostro uma mensagem
   avisando que não há projetos. Sem isso o programa simplesmente não imprimia
   nada, o que dá a impressão de que travou.
2. **Lista com itens.** Percorro com um laço `for` e imprimo cada projeto
   numerado.

Para a numeração usei uma variável contadora iniciada em `1`, incrementada com
`contador += 1` ao final de cada iteração. Precisei começar em 1, e não em 0,
porque a numeração é para leitura humana — ninguém quer ver "projeto 0".

Aqui percebi a diferença entre o `while` e o `for`. O `while` do programa
principal roda por tempo indeterminado, até o usuário mandar sair. O `for` do
`LIST` roda uma quantidade exata de vezes, uma para cada item da lista. Escolher
o laço certo depende de eu saber, ou não, quantas repetições vão acontecer.

### Testes que fiz

- `LIST` com a lista vazia logo ao abrir o programa
- `ADD` com nome de uma palavra e com nome de várias palavras
- `ADD` sem informar nome nenhum
- `LIST` depois de adicionar dois projetos, conferindo a numeração 1 e 2
- Um comando inexistente (`BANANA`) para checar o `else`
- Enter vazio, sem digitar nada
- `EXIT` para encerrar

Todos passaram.

### O que ficou pendente

A memória é volátil: quando fecho o programa, tudo se perde. O próximo passo
natural seria gravar os projetos em um arquivo para que sobrevivessem ao
encerramento. Também gostaria de ter um comando para remover um projeto pelo
número exibido no `LIST`.

---

## Reflexão geral até aqui

O que mais me marcou nesta etapa foi perceber que "dar memória" ao programa não
exigiu nenhuma estrutura complicada — foi uma lista e uma decisão de onde
colocá-la. A dificuldade não estava na sintaxe, e sim em entender o fluxo de
execução e o que acontece a cada volta do laço.

Também comecei a testar cada alteração assim que a faço, em vez de escrever
tudo e rodar no final. Quando um erro aparece, sei exatamente qual linha
mudei.
