# Star Wars Movie Explorer 🚀

## 📋 Sobre o Projeto

Aplicação web que exibe informações sobre os filmes de Star Wars, consumindo uma API REST para mostrar detalhes como personagens, datas de lançamento, diretores e sinopses.

## 🛠 Tecnologias Utilizadas

- Frontend:
  - HTML5
  - CSS3
  - JavaScript (ES6+)
  - Bootstrap 5.3.3
- Backend:
  - PHP 7.4+
  - API REST
- Banco de Dados:
  - MySQL

## ⚙ Funcionalidades

- Listagem de filmes Star Wars
- Exibição de detalhes dos filmes:
  - Título
  - Episódio
  - Data de lançamento
  - Idade do filme (calculada automaticamente)
  - Diretor
  - Produtores
  - Sinopse
  - Lista de personagens
- Modal com informações detalhadas
- Sistema de cache para personagens
- Layout responsivo

## 🎨 Interface

- Design responsivo com Bootstrap
- Animações e transições CSS
- Modal para detalhes dos filmes
- Cards interativos
- Efeitos hover
- Navegação intuitiva

## 🚀 Como Executar

1. Requisitos:

   - PHP 7.4+
   - MySQL
   - Servidor Apache (XAMPP recomendado)

2. Instalação:

   ```bash
   # Clone o repositório
   git clone [url-do-repositorio]

   # Copie os arquivos para pasta htdocs do XAMPP
   C:/xampp/htdocs/teste-tecnico/

   ```

3. Como executar

- Acesse o index.html dentro da pasta `src`
- ou se você clonou para o xampp direto , entre através do localhost `http://localhost/teste-tecnico/src/lista.html`

4. 💻 Endpoints da API

- GET /api/films - Lista todos os filmes
- GET /api/films/{id} - Detalhes do filme
- GET /api/people/{id} - Informações de personagem

5. 🔍 Recursos Adicionais

- Cache de requisições
- Tratamento de erros
- Cálculo automático de idade dos filmes
- Sistema de loading

6. 👨‍💻 Desenvolvido por
   [Kaique alves(jesse)]
