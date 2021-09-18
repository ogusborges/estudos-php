<?php
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once "../database/connection.php";
        $pdo = mysqlConnect();
        $error_message = "";

        $email = isset($_POST["email"]) && !empty($_POST["email"]) ? $_POST["email"] : false;
        $senha = isset($_POST["senha"]) && !empty($_POST["senha"]) ? $_POST["senha"] : false;

        if($email && $senha) {
            try {
                $sql = <<<SQL
                    SELECT
                        senha
                    FROM
                        usuario_t6
                    WHERE
                        email = ?;
                SQL;
                
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$email]);

                $usuario = $stmt->fetch();

                if(!$usuario) {
                    $error_message = "Não foi possível encontrar um usuário com os dados informados.";
                } else if( !password_verify($senha, $usuario["senha"]) ) {
                    $error_message = "Senha incorreta.";
                }
            } catch(Exception $e) {
                if($e->errorInfo[1] === 1062) {
                    $error_message = $e->getMessage();
                }
            }
        } else {
            $error_message = "É necessário preencher um e-mail e senha.";
        }
    } 
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Testar Login</title>
    <meta name="description" content="Exercício 3 do Trabalho 6 de Programação para Internet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>body { height: 100vh; }</style>
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center h-100">
        <main class="form-signin" style="width: 30%;">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
                <div class="card p-3">
                    <img class="m-auto mt-4" src="../images/Ufu_logo.svg" alt="Logotipo da UFU." width="72" height="57">
                    <div class="card-body text-center">
                        <h1 class="h3 mb-3 fw-normal">Teste de Login</h1>

                        <?php
                            if(!empty($error_message)) {
                                $error_html = <<<HTML
                                    <div class="alert alert-danger my-3" role="alert">
                                        $error_message
                                    </div>
                                HTML;

                                echo $error_html;
                            } else if($_SERVER["REQUEST_METHOD"] == "POST" && empty($error_message)) {
                                $success_html = <<<HTML
                                    <div class="alert alert-success my-3" role="alert">
                                        O usuário foi inserido corretamente na base de dados.
                                    </div>
                                HTML;

                                echo $success_html;
                            }
                        ?>

                        <form class="form-floating" action="<?php echo "{$_SERVER["PHP_SELF"]}" ?>" method="POST" autocomplete="off">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="cadastro-email" placeholder="Digite o e-mail" name="email">
                                <label for="cadastro-email">E-mail</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="cadastro-senha" placeholder="Digite a senha" name="senha">
                                <label for="cadastro-senha">Senha</label>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Testar</button>
                            </div>
                            <div class="row mt-1">
                                <a href="index.html" class="link-primary text-decoration-none text-center mt-3">Voltar para a página inicial</a>
                            </div>
                        </form>
                    </div>
                </div>
            </main>
        </div>
</body>
</html>