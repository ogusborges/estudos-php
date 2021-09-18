<?php
    $contagem = count($_POST);
    $quantidade = 0;

    foreach($_POST as $chave => $valor) {
        if(empty($valor)) {
            $quantidade++;
        }
    }

    $error_count = 0;

    $cep = isset($_POST["cep"]) && !empty(trim($_POST["cep"])) ? trim($_POST["cep"]) : $error_count++;
    $logradouro = isset($_POST["logradouro"]) && !empty(trim($_POST["logradouro"])) ? trim($_POST["logradouro"]) : $error_count++;
    $bairro = isset($_POST["bairro"]) && !empty(trim($_POST["bairro"])) ? trim($_POST["bairro"]) : $error_count++;
    $cidade = isset($_POST["cidade"]) && !empty(trim($_POST["cidade"])) ? trim($_POST["cidade"]) : $error_count++;
    $estado = isset($_POST["estado"]) && !empty(trim($_POST["estado"])) ? trim($_POST["estado"]) : $error_count++;

    if($contagem == $quantidade || $error_count != 0) {
        header("Location: http://gustavoboliveira.atwebpages.com/trabalho6/exercicio2/", TRUE, 200);
        exit();
    }

    require_once "../database/connection.php";

    $pdo = mySqlConnect();

    try {
        $sql = <<<SQL
            INSERT INTO endereco_t6 (cep, endereco, bairro, cidade, estado)
            VALUES(?, ?, ?, ?, ?);
        SQL;

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $cep, $logradouro, $bairro, $cidade, $estado
        ]);
        
        
    } catch (Exception $e) {  
        if ($e->errorInfo[1] === 1062)
          exit('Dados duplicados: ' . $e->getMessage());
        else
          exit('Falha ao cadastrar os dados: ' . $e->getMessage());
    }
?>