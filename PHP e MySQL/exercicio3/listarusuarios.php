<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Exercício 3 do Trabalho 6 - Programação para Internet">
    <title>Exercício 3 - Listagem de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <div class="container">
        <div class="card mt-5 rounded-lg">
            <div class="card-header bg-primary text-white">Usuários</div>
            <div class="card-body">
                <h6 class="card-title">Listagem de usuários cadastrados no exercício 3</h6>
                <table class="table table-striped table-hover table-bordered rounded-lg mt-3">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Senha</th>
                        <tr>
                    </thead>
                    <tbody>
                    <?php
                        require_once "../database/connection.php";
                        $pdo = mySqlConnect();

                        try {
                            $stmt = <<<SQL
                                SELECT
                                    email
                                    ,senha
                                FROM
                                    usuario_t6;
                            SQL;
                            
                            $stmt = $pdo->query($stmt);
                            
                            $tbl_ind = 1;

                            while($row = $stmt->fetch()) {
                                $email = $row["email"];
                                $senha = $row["senha"];

                                echo <<<TBL
                                    <tr>
                                        <th scope="row">$tbl_ind</th>
                                        <td>$email</td>
                                        <td>$senha</td>
                                    </tr>
                                TBL;

                                $tbl_ind++;
                            }
                        } catch(Exception $e) {
                            exit('Ocorreu uma falha: ' . $e->getMessage());
                        }
                    ?>
                    </tbody>
                </table>
                <button id="voltar" type="button" class="btn btn-primary">Voltar para a página de cadastro</button>
                <script>
                    document.querySelector('#voltar').onclick = () => { 
                        window.location.href = 'http://gustavoboliveira.atwebpages.com/trabalho6/exercicio3'; };
                </script>
            </div>
        </div>
    </div>
</body>