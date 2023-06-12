# Exemplo de criação de template PDF com PHP e TCPDF

Este projeto demonstra um exemplo simples de como criar um template de PDF utilizando PHP e a biblioteca TCPDF. O objetivo é gerar um arquivo PDF com várias imagens distribuídas horizontalmente em uma página, além de adicionar uma linha no topo da página como marcação para montagem.

## Requisitos

Antes de utilizar este projeto, certifique-se de ter os seguintes requisitos:

- PHP instalado na sua máquina
- Pacote `composer` instalado

## Instalação

Siga os passos abaixo para instalar e executar o projeto:

1. Clone o repositório para o seu ambiente local:

```
git clone https://github.com/seu-usuario/nome-do-repositorio.git
```

2. Navegue até o diretório do projeto:

```
cd nome-do-repositorio
```

3. Instale as dependências do projeto utilizando o composer:

```
composer install
```

## Utilização

Após a conclusão da instalação, você pode utilizar o projeto executando o arquivo `example.php`. Certifique-se de ter uma imagem chamada `imagem.png` no diretório do projeto.

O arquivo `example.php` contém o código necessário para criar o template de PDF. Nele, você pode configurar as dimensões do PNG e do PDF, além de personalizar as informações do documento PDF, como autor, título, cabeçalho e rodapé.

Após a execução do script, um arquivo chamado `template.pdf` será gerado no diretório do projeto, contendo as imagens distribuídas horizontalmente e uma linha no topo da página.

## Personalização

Você pode personalizar este projeto de acordo com as suas necessidades. Algumas possíveis personalizações incluem:

- Alterar as dimensões do PNG e do PDF para se adequarem às suas necessidades.
- Modificar as informações do documento PDF, como autor, título, cabeçalho e rodapé.
- Personalizar a aparência das imagens, como tamanho, posição e estilo.

## Contribuição

Contribuições são bem-vindas! Se você encontrou um problema ou deseja adicionar um novo recurso, sinta-se à vontade para abrir uma nova issue ou enviar um pull request.

## Licença

Este projeto está licenciado sob a licença [MIT](LICENSE). Sinta-se à vontade para utilizar, modificar e distribuir o código conforme necessário.
