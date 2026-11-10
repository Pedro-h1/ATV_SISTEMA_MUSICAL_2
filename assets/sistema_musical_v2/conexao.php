<?php
/*
 * =============================================
 * ARQUIVO: conexao.php
 * FUNÇÃO: Conectar o PHP ao banco de dados MySQL
 * =============================================
 */

// Configurações do banco de dados
$servername = "localhost";
$username   = "root";
$password   = "Senai@118";
$dbname     = "sistema_musical_v2";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando se a conexão funcionou
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Definindo o charset
$conn->set_charset("utf8mb4");

// Criando o diretório de imagens se não existir
if (!file_exists("img")) {
    mkdir("img", 0777, true);
}