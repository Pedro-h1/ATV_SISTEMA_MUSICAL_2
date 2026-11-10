# Sistema Musical — Fase 2

Projeto PHP + MySQL baseado no roteiro da Fase 2 fornecido pelo professor.

## Arquivos principais

- `bd.sql` — criação do banco e das tabelas.
- `conexao.php` — conexão com o MySQL.
- `geraSenha.php` — geração de hash bcrypt.
- `valida_sessao.php` — proteção das páginas internas.
- `login.php` e `logout.php` — autenticação e encerramento da sessão.
- `index.php` — menu principal.
- `cadastro_artista.php` — CRUD de artistas e upload de fotos.
- `cadastro_musica.php` — CRUD de músicas, link do YouTube e upload de capas.
- `listagem_musicas.php` — biblioteca com filtros.
- `styles.css` — folha de estilos original do projeto.
- `img/` — imagens existentes e imagens enviadas pelo sistema.

## Configuração local

1. Coloque a pasta do projeto dentro da pasta pública do Apache.
2. Confira as credenciais do MySQL no arquivo `conexao.php`.
3. O banco utilizado pelo projeto é `sistema_musical_v2`.
4. Se o banco ainda não existir, execute `bd.sql` uma única vez no MySQL.
5. Acesse `login.php` pelo navegador.
6. O usuário inicial definido no script é `admin`, com senha `123`.

**Atenção:** se o banco `sistema_musical_v2` já existe e contém seus dados, não execute `bd.sql` novamente. O script foi feito para criar um banco novo e pode apresentar erro porque as tabelas já existem.

## Requisitos

- PHP com MySQLi.
- MySQL.
- Extensão GD do PHP habilitada para redimensionar fotos e capas.
- Apache configurado para executar PHP.

## Observações

- As páginas internas usam `valida_sessao.php`.
- `login.php` e `logout.php` não usam a proteção de sessão, pois precisam funcionar antes do login e no encerramento da sessão.
- Após cadastros e alterações bem-sucedidas, o sistema redireciona para evitar que atualizar a página repita o envio do formulário.
- Os links e classes visuais existentes foram mantidos sempre que possível.
