<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Exercício 2 do Trabalho 6 - Programação para Internet">
    <title>Exercício 2 - Listagem de Endereços</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <div class="container">
        <div class="card mt-5 rounded-lg">
            <div class="card-header bg-primary text-white">Endereços</div>
            <div class="card-body">
                <h6 class="card-title">Listagem de endereços cadastrados no exercício 2</h6>
                <table class="table table-striped table-hover table-bordered rounded-lg mt-3">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">CEP</th>
                            <th scope="col">Logradouro</th>
                            <th scope="col">Bairro</th>
                            <th scope="col">Cidade</th>
                            <th scope="col">Estado</th>
                        <tr>
                    </thead>
                    <tbody>
                    <?php
                        require_once "../database/connection.php";
                        $pdo = mySqlConnect();

                        try {
                            $stmt = <<<SQL
                                SELECT
                                    cep
                                    ,endereco
                                    ,bairro
                                    ,cidade
                                    ,estado
                                FROM
                                    endereco_t6;
                            SQL;
                            
                            $stmt = $pdo->query($stmt);
                            
                            $tbl_ind = 1;

                            while($row = $stmt->fetch()) {
                                $cep = htmlspecialchars($row["cep"]);
                                $endereco = htmlspecialchars($row["endereco"]);
                                $bairro = htmlspecialchars($row["bairro"]);
                                $cidade = htmlspecialchars($row["cidade"]);
                                $estado = htmlspecialchars($row["estado"]);

                                echo <<<TBL
                                    <tr>
                                        <th scope="row">$tbl_ind</th>
                                        <td>$cep</td>
                                        <td>$endereco</td>
                                        <td>$bairro</td>
                                        <td>$cidade</td>
                                        <td>$estado</td>
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
                        window.location.href = 'http://gustavoboliveira.atwebpages.com/trabalho6/exercicio2'; };
                </script>
            </div>
        </div>
    </div>
</body>