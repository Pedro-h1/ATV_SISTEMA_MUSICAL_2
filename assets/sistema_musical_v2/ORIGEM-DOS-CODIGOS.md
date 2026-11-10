# Origem dos códigos e alterações

Este documento identifica a origem dos principais trechos da versão atualizada.

## Base preservada do projeto enviado

- `styles.css`: mantido sem alterações.
- Pasta `img/`: as 30 imagens que estavam no ZIP foram preservadas.
- `bd.sql`: mantido como enviado. A estrutura segue a Etapa 1 do roteiro do professor.
- `conexao.php`: mantido com as configurações locais que já estavam funcionando no ZIP.
- `geraSenha.php`: mantido.
- `valida_sessao.php`: mantido.
- `login.php`: mantido a partir da versão que já funcionava no projeto.
- `logout.php`: mantido a partir da versão que já funcionava no projeto.

## Códigos baseados diretamente no roteiro do professor

- `index.php`: estrutura do menu da Etapa 7; foi ligada à variável de sessão para exibir o usuário conectado, como prevê o roteiro.
- `cadastro_artista.php`: consulta e tabela da Etapa 8a; cadastro da Etapa 8b; edição/exclusão da Etapa 8c; upload/redimensionamento da Etapa 8d.
- `cadastro_musica.php`: função do YouTube e listagem da Etapa 9a; cadastro/edição/exclusão das etapas 9b e 9c; upload/redimensionamento da capa da Etapa 9d.
- `listagem_musicas.php`: filtros dinâmicos, consulta, exclusão e tabela da Etapa 10.

## Complementos feitos por conta própria

Os seguintes ajustes foram acrescentados para completar ou corrigir o funcionamento, mantendo os recursos e a estrutura ensinados no roteiro:

1. Redirecionamento após cadastro ou edição bem-sucedida (`header()` + `exit()`), para que atualizar a página não envie o mesmo formulário novamente.
2. Redirecionamento após exclusões, evitando repetir a operação ao atualizar.
3. Mensagens de retorno após os redirecionamentos.
4. Verificação de campos obrigatórios no PHP, além do `required` do HTML.
5. Verificação de que o link informado corresponde a um formato de vídeo do YouTube reconhecido pela função do roteiro.
6. Preservação da foto/capa atual quando o formulário de edição é enviado sem uma nova imagem.
7. Remoção do arquivo de imagem associado quando um artista ou música é excluído.
8. Exibição da foto/capa na tabela quando existe arquivo salvo.
9. Exibição do nome do usuário conectado no menu.
10. `README-ENTREGA.md` com instruções de configuração e execução.

## Observações importantes

- A sintaxe e as classes CSS utilizadas foram mantidas dentro dos padrões já presentes no roteiro e nos arquivos do projeto.
- A validação feita nesta entrega inclui verificação de sintaxe PHP (`php -l`). Isso não substitui o teste no Apache/MySQL do computador onde o projeto será executado.
- A extensão GD do PHP precisa estar habilitada para o redimensionamento das imagens.
- Não execute `bd.sql` novamente se o banco `sistema_musical_v2` já estiver criado e contendo os dados atuais.
