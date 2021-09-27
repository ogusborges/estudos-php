<?php
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once "../database/connection.php";
        $pdo = mysqlConnect();
        $error_message = "";

        //Dados pessoais
        $nome = isset($_POST["nome"]) && !empty($_POST["nome"]) ? $_POST["nome"] : false;
        $email = isset($_POST["email"]) && !empty($_POST["email"]) ? $_POST["email"] : false;
        $sexo = isset($_POST["sexo"]) && !empty($_POST["sexo"]) ? $_POST["sexo"] : false;
        $altura = isset($_POST["altura"]) && !empty($_POST["altura"]) ? $_POST["altura"] : false;
        $peso = isset($_POST["peso"]) && !empty($_POST["peso"]) ? $_POST["peso"] : false;
        $sang = isset($_POST["tipo-sanguineo"]) && !empty($_POST["tipo-sanguineo"]) ? $_POST["tipo-sanguineo"] : false;
        $telefone = isset($_POST["telefone"]) && !empty($_POST["telefone"]) ? $_POST["telefone"] : false;

        //Endereço
        $cep = isset($_POST["cep"]) && !empty($_POST["cep"])? $_POST["cep"] : false;
        $logradouro = isset($_POST["logradouro"]) && !empty($_POST["logradouro"]) ? $_POST["logradouro"] : false;
        $cidade = isset($_POST["cidade"]) && !empty($_POST["cidade"]) ? $_POST["cidade"] : false;
        $estado = isset($_POST["estado"]) && !empty($_POST["estado"]) ? $_POST["estado"] : false;
        
        if($nome && $email && $sexo && $altura && $peso && $sang && $cep && $logradouro && $cidade && $estado) {
            try {
                $pdo->beginTransaction();

                $sql_pessoa = <<<SQL
                    INSERT INTO pessoa_t6 (nome, email, sexo, telefone, cep, logradouro, cidade, estado)
                    VALUES(?, ?, ?, ?, ?, ?, ?, ?);
                SQL;

                $sql_paciente = <<<SQL
                    INSERT INTO paciente_t6(pessoa_id, altura, peso, tipo_sanguineo)
                    VALUES (?, ?, ?, ?);
                SQL;
                
                $stmt_pessoa = $pdo->prepare($sql_pessoa);
                $res = $stmt_pessoa->execute([$nome, $email, $sexo, $telefone, $cep, $logradouro, $cidade, $estado]);
                if(!$res) { 
                    throw new Exception("Não foi possível inserir os dados pessoais.");
                }

                $pessoa_id = $pdo->lastInsertId();
                $stmt_paciente = $pdo->prepare($sql_paciente);
                $res = $stmt_paciente->execute([$pessoa_id, $altura, $peso, $sang]);

                if(!$res) { 
                    throw new Exception("Não foi possível inserir os dados de paciente."); 
                }

            } catch(Exception $e) {
                $pdo->rollBack();

                if($e->errorInfo[1] === 1062) {
                    $error_message = "Já existe um usuário com o endereço de e-mail informado.";
                } else {
                    $error_message = $e->getMessage();
                }
            }
        } else {
            $error_message = "É necessário preencher todos os dados solicitados.";
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Exercício 4 do Trabalho 6 - Programação para Internet">
    <title>Exercício 4 - Cadastro de Pacientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        body { height: 100vh; }
    </style>
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <div class="container d-flex align-items-center justify-content-center h-100">
        <form class="form-floating" action="<?php echo "{$_SERVER["PHP_SELF"]}" ?>" method="POST" autocomplete="off">
            <?php
                if(!empty($error_message)) {
                    $error_html = <<<HTML
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-danger my-3" role="alert">
                                    $error_message
                                </div>
                            </div>
                        </div>
                    HTML;

                    echo $error_html;
                } else if($_SERVER["REQUEST_METHOD"] == "POST" && empty($error_message)) {
                    $success_html = <<<HTML
                        <div class="alert alert-success my-3" role="alert" id="alerta-sucesso">
                        </div>

                        <script type="text/javascript">
                            const divSucesso = document.querySelector('#alerta-sucesso');
                            let tempo = 10;
                            
                            function disparaIntervalo() {
                                setInterval( () => {
                                    if(tempo == 0) {
                                        window.location.href = 'http://gustavoboliveira.atwebpages.com/trabalho6/exercicio4';
                                    } else {
                                        divSucesso.innerText = 'Paciente Cadastrado com sucesso. Redirecionando para a página principal em ' + tempo + '...';
                                        tempo--;
                                    }
                                }, 1000);
                            }

                            disparaIntervalo();
                        </script>
                    HTML;

                    echo $success_html;
                }
            ?>
        
            <div class="row">
                <div class="col-12">
                    <div class="card rounded-lg">
                        <div class="card-header bg-primary text-white">
                            Dados Pessoais
                        </div>
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-md-7">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="cadastro-nome"
                                            placeholder="Digite o nome completo" name="nome">
                                        <label for="cadastro-nome">Nome Completo</label>
                                    </div>

                                </div>
                                <div class="col-md-5">
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="cadastro-email"
                                            placeholder="Digite o e-mail" name="email">
                                        <label for="cadastro-email">E-mail</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-floating">
                                        <select class="form-select" id="cadastro-sexo" name="sexo">
                                            <option value="" selected>Selecione um sexo</option>
                                            <option value="M">Masculino</option>
                                            <option value="F">Feminino</option>
                                        </select>
                                        <label for="cadastro-sexo">Sexo</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating">
                                        <input type="number" step="any" class="form-control" id="cadastro-altura"
                                            placeholder="Digite a altura em metros" name="altura">
                                        <label for="cadastro-altura">Altura (em metros)</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating">
                                        <input type="number" step="any" class="form-control" id="cadastro-peso"
                                            placeholder="Digite o peso em kg" name="peso">
                                        <label for="cadastro-peso">Peso (em kg)</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-floating">
                                        <select class="form-select" id="cadastro-tiposanguineo" name="tipo-sanguineo">
                                            <option value="" selected>Selecione um tipo</option>
                                            <option value="A+">A+</option>
                                            <option value="A-">A-</option>
                                            <option value="B+">B+</option>
                                            <option value="B-">B-</option>
                                            <option value="AB+">AB+</option>
                                            <option value="AB-">AB-</option>
                                            <option value="O+">O+</option>
                                            <option value="O-">O-</option>
                                        </select>
                                        <label for="cadastro-tiposanguineo">Tipo Sanguíneo</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="cadastro-telefone"
                                            placeholder="Digite o telefone" name="telefone">
                                        <label for="cadastro-telefone">Telefone</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card mt-3 rounded-lg">
                        <div class="card-header bg-primary text-white">
                            Endereço
                        </div>
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-floating mb-3">
                                        <input type="number" class="form-control" id="cadastro-cep"
                                            placeholder="Digite o CEP" name="cep">
                                        <label for="cadastro-cep">CEP</label>
                                    </div>

                                </div>
                                <div class="col-md-8">
                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="cadastro-logradouro"
                                            placeholder="Digite o logradouro" name="logradouro">
                                        <label for="cadastro-logradouro">Logradouro</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="cadastro-cidade"
                                            placeholder="Digite a cidade" name="cidade">
                                        <label for="cadastro-cidade">Cidade</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="cadastro-estado" name="estado">
                                            <option value="" selected>Selecione um estado</option>
                                            <option value="AC">Acre</option>
                                            <option value="AL">Alagoas</option>
                                            <option value="AP">Amapá</option>
                                            <option value="AM">Amazonas</option>
                                            <option value="BA">Bahia</option>
                                            <option value="CE">Ceará</option>
                                            <option value="DF">Distrito Federal</option>
                                            <option value="ES">Espírito Santo</option>
                                            <option value="GO">Goiás</option>
                                            <option value="MA">Maranhão</option>
                                            <option value="MT">Mato Grosso</option>
                                            <option value="MS">Mato Grosso do Sul</option>
                                            <option value="MG">Minas Gerais</option>
                                            <option value="PA">Pará</option>
                                            <option value="PB">Paraíba</option>
                                            <option value="PR">Paraná</option>
                                            <option value="PE">Pernambuco</option>
                                            <option value="PI">Piauí</option>
                                            <option value="RJ">Rio de Janeiro</option>
                                            <option value="RN">Rio Grande do Norte</option>
                                            <option value="RS">Rio Grande do Sul</option>
                                            <option value="RO">Rondônia</option>
                                            <option value="RR">Roraima</option>
                                            <option value="SC">Santa Catarina</option>
                                            <option value="SP">São Paulo</option>
                                            <option value="SE">Sergipe</option>
                                            <option value="TO">Tocantins</option>
                                            <option value="ET">Estrangeiro</option>
                                        </select>
                                        <label for="cadastro-estado">Estado</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success mt-3">Cadastrar</button>
            <button type="reset" class="btn btn-danger mt-3">Limpar</button>
            <button type="reset" class="btn btn-primary mt-3" id="voltar">Voltar</button>
        </form>
        <script>
            document.querySelector('#voltar').onclick = () => { 
                window.location.href = 'http://gustavoboliveira.atwebpages.com/trabalho6/exercicio4'; };
        </script>
    </div>
</body>