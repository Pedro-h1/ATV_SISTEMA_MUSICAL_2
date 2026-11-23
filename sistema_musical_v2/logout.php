<?php
/*
 * =============================================
 * ARQUIVO: logout.php
 * FUNÇÃO: Encerrar a sessão do usuário
 * =============================================
 */

// Passo 1: Inicia a sessão
session_start();

// Passo 2: Esvazia os dados da sessão
$_SESSION = array();

// Passo 3: Remove o cookie da sessão
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Passo 4: Destrói a sessão
session_destroy();

// Passo 5: Volta para o login com mensagem de sucesso
header('Location: login.php?logout=success');
exit();