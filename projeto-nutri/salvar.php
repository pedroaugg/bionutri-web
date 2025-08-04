<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); // ajuste conforme segurança desejada

// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "imc_db");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["erro" => "Erro na conexão com o banco de dados"]);
    exit();
}

// Receber os dados JSON do JavaScript
$data = json_decode(file_get_contents("php://input"), true);

$nome = $conn->real_escape_string($data["nome"]);
$idade = (int) $data["idade"];
$peso = (float) $data["peso"];
$altura = (float) $data["altura"];
$imc = $peso / ($altura * $altura);

if ($imc < 18.5) {
    $classificacao = "Abaixo do peso";
} elseif ($imc < 24.9) {
    $classificacao = "Peso normal";
} elseif ($imc < 29.9) {
    $classificacao = "Sobrepeso";
} else {
    $classificacao = "Obesidade";
}

// Inserir no banco
$sql = "INSERT INTO imc_dados (nome, idade, peso, altura, imc, classificacao)
        VALUES ('$nome', $idade, $peso, $altura, $imc, '$classificacao')";

if ($conn->query($sql) === TRUE) {
    echo json_encode([
        "mensagem" => "$nome, com $idade anos, seu IMC é " . round($imc, 2) . " ($classificacao)."
    ]);
} else {
    http_response_code(500);
    echo json_encode(["erro" => "Erro ao salvar os dados"]);
}

$conn->close();
