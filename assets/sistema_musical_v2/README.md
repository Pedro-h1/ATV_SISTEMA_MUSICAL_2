<div align="center">

<img src="https://capsule-render.vercel.app/api?type=waving&height=260&color=8B5CF6&text=🎼+Sistema%20Musical&fontColor=FFFFFF&fontSize=48&fontAlignY=38&animation=fadeIn&desc=💻+Part+2+%E2%80%94+Back-End+%26+Database&descAlignY=58&descSize=18" width="100%" />

</div>

<div align="center">

<img src="https://readme-typing-svg.demolab.com?font=Fira+Code&size=24&duration=8000&pause=1500&color=8B5CF6&center=true&vCenter=true&width=750&lines=Sobre+o+Projeto;Sistema+Musical+-+Part+2" alt="Sobre o Projeto" />

</div>

<div align="center">

O **Sistema Musical** é um projeto desenvolvido como atividade do **SENAI**, dando continuidade à primeira parte, voltada à construção das interfaces do sistema.

Nesta segunda etapa, o projeto recebe funcionalidades de **Back-End**, utilizando **PHP e MySQL** para realizar a autenticação de usuários e o gerenciamento de artistas e músicas.

</div>

---

<div align="center">

<img src="https://readme-typing-svg.demolab.com?font=Fira+Code&size=24&duration=8000&pause=1500&color=8B5CF6&center=true&vCenter=true&width=750&lines=Objetivo+do+Projeto" alt="Objetivo do Projeto" />

</div>

<div align="center">

Implementar a parte funcional do Sistema Musical, conectando as páginas ao banco de dados e permitindo cadastrar, consultar, editar e excluir informações de artistas e músicas.

</div>

---

<div align="center">

<img src="https://readme-typing-svg.demolab.com?font=Fira+Code&size=24&duration=8000&pause=1500&color=8B5CF6&center=true&vCenter=true&width=750&lines=Funcionalidades" alt="Funcionalidades" />

</div>

<div align="center">

🔐 **Login e sessão:** autenticação de usuários e proteção das páginas internas.<br>
🎤 **Artistas:** cadastro, consulta, edição, exclusão e envio de imagens.<br>
🎼 **Músicas:** cadastro, consulta, edição e exclusão de músicas.<br>
🖼️ **Imagens:** envio e redimensionamento de fotos de artistas e capas.<br>
▶️ **YouTube:** acesso aos vídeos associados às músicas.<br>
🔎 **Filtros:** busca de músicas por texto, artista e gênero.

</div>

---

<div align="center">

<img src="https://readme-typing-svg.demolab.com?font=Fira+Code&size=24&duration=8000&pause=1500&color=8B5CF6&center=true&vCenter=true&width=750&lines=Arquivos+do+Projeto" alt="Arquivos do Projeto" />

</div>

<div align="center">

| Arquivo | Descrição |
|:---:|:---|
| `index.php` | Página inicial do sistema |
| `login.php` | Tela de login e validação do usuário |
| `logout.php` | Encerramento da sessão |
| `valida_sessao.php` | Proteção das páginas internas |
| `conexao.php` | Conexão com o banco de dados MySQL |
| `geraSenha.php` | Geração de hash para senhas |
| `cadastro_artista.php` | Cadastro e gerenciamento de artistas |
| `cadastro_musica.php` | Cadastro e gerenciamento de músicas |
| `listagem_musicas.php` | Listagem e filtros de músicas |
| `bd.sql` | Criação do banco de dados e das tabelas |
| `styles.css` | Estilização compartilhada das páginas |
| `img/` | Imagens utilizadas pelo sistema |

</div>

---

<div align="center">

<img src="https://readme-typing-svg.demolab.com?font=Fira+Code&size=24&duration=8000&pause=1500&color=8B5CF6&center=true&vCenter=true&width=750&lines=Especifica%C3%A7%C3%B5es+T%C3%A9cnicas" alt="Especificações Técnicas" />

</div>

<div align="center">

| Tecnologia | Utilização |
|:---:|:---|
| PHP | Lógica do Back-End e operações do sistema |
| MySQL | Armazenamento das informações |
| HTML5 | Estruturação das páginas |
| CSS3 | Estilização e organização visual |
| Apache | Servidor local para executar o PHP |
| Visual Studio Code | Ambiente de desenvolvimento |

<br>

**Banco de Dados:** `sistema_musical_v2`.

**Ambiente de desenvolvimento:** Apache, PHP e MySQL.

</div>

---

<div align="center">

<img src="https://readme-typing-svg.demolab.com?font=Fira+Code&size=24&duration=8000&pause=1500&color=8B5CF6&center=true&vCenter=true&width=750&lines=Como+Executar" alt="Como Executar" />

</div>

1. Coloque os arquivos do projeto na pasta pública do Apache.
2. Configure usuário e senha do MySQL no arquivo `conexao.php`.
3. Crie o banco `sistema_musical_v2` executando o arquivo `bd.sql` apenas na primeira configuração.
4. Inicie o Apache e o MySQL.
5. Acesse `http://localhost/` seguido do caminho da pasta do projeto e abra `login.php`.

**Atenção:** o arquivo `conexao.php` deste repositório contém um campo de exemplo (`SUA_SENHA_DO_MYSQL`). Substitua-o pela senha do seu ambiente local antes de executar. Não publique senhas reais.

**Acesso inicial do banco recém-criado:** usuário `admin` e senha `123`.

**Requisito adicional:** a extensão GD do PHP deve estar habilitada para o redimensionamento das imagens.

</div>

<div align="center">
<img src="https://capsule-render.vercel.app/api?type=waving&height=140&color=8B5CF6&section=footer" width="100%" />
</div>
