<?php
    require "../database/connection.php";
    $pdo = mysqlConnect();

    $nome = $_POST["nome"] ?? "";
    $cpf = $_POST["cpf"] ?? "";
    $email = $_POST["email"] ?? "";
    $senha = $_POST["senha"] ?? "";
    $altura = $_POST["altura"] ?? "";
    $estadocivil = $_POST["estadocivil"] ?? "";
    $datanascimento = $_POST["datanascimento"] ?? "";

    // calcula um hash de senha seguro para armazenar no BD
    $hashsenha = password_hash($senha, PASSWORD_DEFAULT);

	try {
		$sql = <<<SQL
		-- Repare que a coluna Id foi omitida por ser auto_increment
		INSERT INTO cliente_t6 (nome, cpf, email, hash_senha, 
							data_nascimento, estado_civil, altura)
		VALUES (?, ?, ?, ?, ?, ?, ?)
		SQL;

		// Neste caso utilize prepared statements para prevenir
		// ataques do tipo SQL Injection, pois precisamos
		// cadastrar dados fornecidos pelo usuário 
		$stmt = $pdo->prepare($sql);
		$stmt->execute([
			$nome, $cpf, $email, $hashsenha,
			$datanascimento, $estadocivil, $altura
		]);

		header("location: index.html");
		exit();
	} catch (Exception $e) {  
		if ($e->errorInfo[1] === 1062)
			exit('Dados duplicados: ' . $e->getMessage());
		else
			exit('Falha ao cadastrar os dados: ' . $e->getMessage());
	}
?>