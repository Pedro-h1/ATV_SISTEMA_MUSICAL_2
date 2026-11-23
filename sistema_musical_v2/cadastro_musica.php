<?php
/*
 * =============================================
 * ARQUIVO: cadastro_musica.php
 * FUNÇÃO: CRUD de músicas + link do YouTube + capa
 * =============================================
 */
include('valida_sessao.php');
include('conexao.php');

// -----------------------------------------------
// FUNÇÃO: Extrair ID do vídeo do YouTube
// -----------------------------------------------
function getYoutubeVideoId(string $url) {
    $patterns = [
        '/youtube\.com\/watch\?v=([^\&\?\/]+)/',
        '/youtube\.com\/embed\/([^\&\?\/]+)/',
        '/youtube\.com\/v\/([^\&\?\/]+)/',
        '/youtu\.be\/([^\&\?\/]+)/'
    ];

    foreach ($patterns as $p) {
        if (preg_match($p, $url, $m)) return $m[1];
    }

    return "";
}


// -----------------------------------------------
// FUNÇÃO: Redimensionar e salvar imagem
// -----------------------------------------------
function redimensionarESalvarImagem(array $arquivo, $largura = 300, $altura = 300) {
    $caminho = "img/" . uniqid() . '_' . basename($arquivo["name"]);
    $tipo = strtolower(pathinfo($caminho, PATHINFO_EXTENSION));

    if (!isset($arquivo["tmp_name"]) || !getimagesize($arquivo["tmp_name"])) {
        return "Arquivo não é uma imagem válida.";
    }
    if ($arquivo["size"] > 5000000) {
        return "Imagem muito grande. Máximo: 5 MB.";
    }
    if (!in_array($tipo, ["jpg", "jpeg", "png"])) {
        return "Apenas JPG e PNG são aceitos.";
    }

    $original = imagecreatefromstring(file_get_contents($arquivo["tmp_name"]));
    if (!$original) {
        return "Não foi possível abrir a imagem.";
    }

    $w0 = imagesx($original);
    $h0 = imagesy($original);
    $ratio = min($largura / $w0, $altura / $h0);
    $nw = (int)($w0 * $ratio);
    $nh = (int)($h0 * $ratio);

    $nova = imagecreatetruecolor($nw, $nh);
    imagecopyresampled($nova, $original, 0, 0, 0, 0, $nw, $nh, $w0, $h0);

    if ($tipo === "png") {
        imagepng($nova, $caminho);
    } else {
        imagejpeg($nova, $caminho, 90);
    }

    imagedestroy($original);
    imagedestroy($nova);

    return $caminho;
}


// -----------------------------------------------
// FILTRO DE ARTISTA
// -----------------------------------------------
$artista_id_filtro = isset($_GET['artista_id']) ? intval($_GET['artista_id']) : 0;
$artistas = $conn->query("SELECT id, nome FROM artistas ORDER BY nome");

$mensagem = null;
$class = null;


// -----------------------------------------------
// CREATE / UPDATE
// -----------------------------------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? '';
    $artista_id = intval($_POST['artista_id'] ?? 0);
    $titulo = trim($_POST['titulo'] ?? '');
    $album = trim($_POST['album'] ?? '');
    $genero = trim($_POST['genero'] ?? '');
    $youtube_link = trim($_POST['youtube_link'] ?? '');
    $letra = trim($_POST['letra'] ?? '');
    $imagem_capa = $_POST['imagem_capa_atual'] ?? '';

    if ($artista_id <= 0 || $titulo == '' || $youtube_link == '') {
        $mensagem = "Preencha o artista, o título e o link do YouTube.";
        $class = "error";
    } elseif (getYoutubeVideoId($youtube_link) == '') {
        $mensagem = "Informe um link válido de vídeo do YouTube.";
        $class = "error";
    } else {
        $erro_imagem = null;

        if (isset($_FILES['imagem_capa']) && $_FILES['imagem_capa']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['imagem_capa']['error'] === UPLOAD_ERR_OK) {
                $nova_imagem = redimensionarESalvarImagem($_FILES['imagem_capa']);
                if (strpos($nova_imagem, "img/") === 0) {
                    if (!empty($imagem_capa) && file_exists($imagem_capa)) {
                        unlink($imagem_capa);
                    }
                    $imagem_capa = $nova_imagem;
                } else {
                    $erro_imagem = $nova_imagem;
                }
            } else {
                $erro_imagem = "Não foi possível enviar a capa.";
            }
        }

        if ($erro_imagem) {
            $mensagem = $erro_imagem;
            $class = "error";
        } else {
            if ($id) {
                // UPDATE: existe um ID oculto → estamos editando
                $stmt = $conn->prepare("UPDATE musicas SET artista_id=?, titulo=?, album=?, genero=?, youtube_link=?, letra=?, imagem_capa=? WHERE id=?");
                $stmt->bind_param("issssssi", $artista_id, $titulo, $album, $genero, $youtube_link, $letra, $imagem_capa, $id);
                $mensagem = "Música atualizada com sucesso!";
            } else {
                // INSERT: sem ID → é um cadastro novo
                $stmt = $conn->prepare("INSERT INTO musicas (artista_id, titulo, album, genero, youtube_link, letra, imagem_capa) VALUES (?,?,?,?,?,?,?)");
                $stmt->bind_param("issssss", $artista_id, $titulo, $album, $genero, $youtube_link, $letra, $imagem_capa);
                $mensagem = "Música cadastrada com sucesso!";
            }

            $class = $stmt->execute() ? "success" : "error";
            if ($class === "error") {
                $mensagem = "Erro: " . $stmt->error;
            }
            $stmt->close();

            // Redireciona após salvar para evitar cadastro duplicado ao atualizar.
            if ($class === "success") {
                header("Location: cadastro_musica.php?artista_id=" . $artista_id . "&status=" . ($id ? "atualizada" : "cadastrada"));
                exit();
            }
        }
    }
}


// -----------------------------------------------
// DELETE
// -----------------------------------------------
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);

    $stmt = $conn->prepare("SELECT imagem_capa FROM musicas WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $registro = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM musicas WHERE id = ?");
    $stmt->bind_param("i", $id);
    $ok = $stmt->execute();
    $stmt->close();

    if ($ok) {
        if ($registro && !empty($registro['imagem_capa']) && file_exists($registro['imagem_capa'])) {
            unlink($registro['imagem_capa']);
        }
        header("Location: cadastro_musica.php?artista_id=" . $artista_id_filtro . "&status=excluida");
        exit();
    } else {
        header("Location: cadastro_musica.php?artista_id=" . $artista_id_filtro . "&status=erro");
        exit();
    }
}


// -----------------------------------------------
// MENSAGENS APÓS REDIRECIONAMENTO
// -----------------------------------------------
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'cadastrada') {
        $mensagem = "Música cadastrada com sucesso!";
        $class = "success";
    } elseif ($_GET['status'] == 'atualizada') {
        $mensagem = "Música atualizada com sucesso!";
        $class = "success";
    } elseif ($_GET['status'] == 'excluida') {
        $mensagem = "Música excluída!";
        $class = "success";
    } else {
        $mensagem = "Não foi possível excluir a música.";
        $class = "error";
    }
}


// -----------------------------------------------
// READ — carregar música para edição e listar músicas
// -----------------------------------------------
$musica = null;
if (isset($_GET['edit_id'])) {
    $eid = intval($_GET['edit_id']);
    $stmt = $conn->prepare("SELECT * FROM musicas WHERE id = ?");
    $stmt->bind_param("i", $eid);
    $stmt->execute();
    $musica = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($musica) {
        $artista_id_filtro = intval($musica['artista_id']);
    }
}

$musicas = null;
if ($artista_id_filtro > 0) {
    $stmt = $conn->prepare("
        SELECT m.*, a.nome AS artista_nome
        FROM musicas m
        JOIN artistas a ON m.artista_id = a.id
        WHERE m.artista_id = ?
        ORDER BY m.titulo
    ");
    $stmt->bind_param("i", $artista_id_filtro);
    $stmt->execute();
    $musicas = $stmt->get_result();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $musica ? 'Editar Música' : 'Nova Música'; ?> — Sistema Musical</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 100 100%27><text y=%27.9em%27 font-size=%2790%27>🎵</text></svg>">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">

    <div class="page-header-bar">
        <h2><?php echo $musica ? '✏️ Editar Música' : '🎵 Nova Música'; ?></h2>
        <div class="header-actions">
            <a href="cadastro_artista.php" class="btn-back">⬅ Voltar para Artistas</a>
            <a href="listagem_musicas.php" class="btn-back">🎼 Biblioteca</a>
        </div>
    </div>

    <?php if (isset($mensagem)): ?>
        <div class="message <?php echo $class; ?>">
            <?php echo $class === 'success' ? '✅' : '⚠️'; ?>
            <?php echo htmlspecialchars($mensagem); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="cadastro_musica.php<?php echo $artista_id_filtro ? '?artista_id=' . $artista_id_filtro : ''; ?>" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $musica['id'] ?? ''; ?>">
        <input type="hidden" name="imagem_capa_atual" value="<?php echo htmlspecialchars($musica['imagem_capa'] ?? ''); ?>">

        <div class="form-grid">

            <div class="form-group">
                <label for="artista_id">Artista <span style="color:var(--red)">*</span></label>
                <select name="artista_id" id="artista_id" required>
                    <option value="">Selecione um artista</option>
                    <?php while ($a = $artistas->fetch_assoc()): ?>
                        <option value="<?php echo $a['id']; ?>"
                            <?php echo ($artista_id_filtro == $a['id'] || (isset($musica['artista_id']) && $musica['artista_id'] == $a['id'])) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($a['nome']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="titulo">Título <span style="color:var(--red)">*</span></label>
                <input type="text" id="titulo" name="titulo"
                       value="<?php echo htmlspecialchars($musica['titulo'] ?? ''); ?>"
                       required placeholder="Ex: Oceano, Aquarela…">
            </div>

            <div class="form-group">
                <label for="album">Álbum</label>
                <input type="text" id="album" name="album"
                       value="<?php echo htmlspecialchars($musica['album'] ?? ''); ?>"
                       placeholder="Ex: Luz (opcional)">
            </div>

            <div class="form-group">
                <label for="genero">Gênero</label>
                <input type="text" id="genero" name="genero"
                       value="<?php echo htmlspecialchars($musica['genero'] ?? ''); ?>"
                       placeholder="Ex: MPB, Rock…">
            </div>

            <div class="form-group">
                <label for="youtube_link">Link do YouTube <span style="color:var(--red)">*</span></label>
                <input type="url" id="youtube_link" name="youtube_link"
                       value="<?php echo htmlspecialchars($musica['youtube_link'] ?? ''); ?>"
                       required placeholder="https://www.youtube.com/watch?v=…">
                <small>Cole a URL do vídeo (não do canal ou playlist).</small>
            </div>

            <div class="form-group">
                <label for="imagem_capa">Capa do Álbum</label>
                <input type="file" id="imagem_capa" name="imagem_capa" accept="image/*">
                <small>JPG ou PNG · Máximo 5 MB<?php echo $musica ? ' · Deixe vazio para manter a capa atual' : ''; ?></small>
                <?php if (!empty($musica['imagem_capa'])): ?>
                    <div class="current-image">
                        <img src="<?php echo htmlspecialchars($musica['imagem_capa']); ?>" alt="Capa atual">
                        <small>Capa atual</small>
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group full">
                <label for="letra">Letra da Música</label>
                <textarea id="letra" name="letra"
                          placeholder="Cole a letra aqui (opcional)…"><?php echo htmlspecialchars($musica['letra'] ?? ''); ?></textarea>
            </div>

        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <?php echo $musica ? '💾 Atualizar' : '➕ Cadastrar'; ?>
            </button>
            <?php if ($musica): ?>
                <a href="cadastro_musica.php?artista_id=<?php echo $artista_id_filtro; ?>"
                   class="btn-back">✖ Cancelar</a>
            <?php endif; ?>
        </div>
    </form>

    <?php if ($artista_id_filtro > 0): ?>
        <h3>🎵 Músicas Cadastradas</h3>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th class="col-capa">Capa</th>
                        <th>Título</th>
                        <th>Álbum</th>
                        <th class="col-youtube">YouTube</th>
                        <th class="col-acoes">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($musicas && $musicas->num_rows > 0): ?>
                        <?php while ($row = $musicas->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($row['imagem_capa'])): ?>
                                        <img src="<?php echo htmlspecialchars($row['imagem_capa']); ?>"
                                             alt="Capa" class="table-thumbnail">
                                    <?php else: ?>
                                        <div class="no-image">Sem<br>capa</div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($row['album']) ?: '—'; ?></td>
                                <td>
                                    <?php
                                    $vid = getYoutubeVideoId($row['youtube_link'] ?? '');
                                    if ($vid):
                                        $watch_url = "https://www.youtube.com/watch?v={$vid}";
                                    ?>
                                        <a href="<?php echo htmlspecialchars($watch_url); ?>"
                                           target="_blank" rel="noopener noreferrer"
                                           class="btn-youtube">▶ Assistir</a>
                                    <?php else: ?>
                                        <span class="text-muted">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="?artista_id=<?php echo $artista_id_filtro; ?>&edit_id=<?php echo $row['id']; ?>"
                                           class="btn-edit">✏️ Editar</a>
                                        <a href="?artista_id=<?php echo $artista_id_filtro; ?>&delete_id=<?php echo $row['id']; ?>"
                                           onclick="return confirm('Excluir <?php echo htmlspecialchars(addslashes($row['titulo'])); ?>?')"
                                           class="btn-delete">🗑️</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="empty-cell">Nenhuma música cadastrada para este artista.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>
</body>
</html>
