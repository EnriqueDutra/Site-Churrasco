<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilo.css">
    <title>Churrasco - Enrique de Abreu Dutra</title>
</head>

<body>
    <header>
        <h1>CHURRASCO - Enrique de Abreu Dutra</h1>
    </header>

    <?php
    // Função para gerar nomes aleatórios
    function gerarNomes($quantidade)
    {
        $nomes = [];
        for ($i = 0; $i < $quantidade; $i++) {
            $nomes[] = "Convidado " . ($i + 1);
        }
        return $nomes;
    }

    // Lista de desejos
    $itens = [
        "carne_de_boi" => ["nome" => "Carne de Boi", "quantidade" => 1, "unidade" => "kg"],
        "frango" => ["nome" => "Frango", "quantidade" => 1, "unidade" => "kg"],
        "peixe" => ["nome" => "Peixe", "quantidade" => 1, "unidade" => "kg"],
        "costela" => ["nome" => "Costela", "quantidade" => 1, "unidade" => "kg"],
        "picanha" => ["nome" => "Picanha", "quantidade" => 1, "unidade" => "kg"],
        "coca_cola" => ["nome" => "Coca-Cola", "quantidade" => 2, "unidade" => "litros"],
        "fanta" => ["nome" => "Fanta", "quantidade" => 2, "unidade" => "litros"],
        "skol" => ["nome" => "Skol", "quantidade" => 1, "unidade" => "litros"],
        "abacaxi" => ["nome" => "Abacaxi", "quantidade" => 1, "unidade" => "unidade"],
        "linguica" => ["nome" => "Linguiça", "quantidade" => 1, "unidade" => "kg"]
    ];

    // Inicializar variáveis
    $numConvidados = 0;
    $mediaConsumo = [];

    // Processar formulário
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $quantidades = [];
        $totalValor = 0;

        // Calcular quantidade total e valor
        foreach ($itens as $item => $info) {
            if (isset($_POST[$item]) && is_numeric($_POST[$item])) {
                $quantidade = $_POST[$item] * $info["quantidade"];
                $valor = calcularValorItem($quantidade, $info["unidade"]);
                $quantidades[$item] = [
                    "quantidade" => $quantidade,
                    "unidade" => $info["unidade"],
                    "valor" => $valor
                ];
                $totalValor += $valor;
            } else {
                $quantidades[$item] = [
                    "quantidade" => 0,
                    "unidade" => $info["unidade"],
                    "valor" => 0
                ];
            }
        }

        // Calcular média de consumo por convidado
        $numConvidados = isset($_POST["num_convidados"]) ? intval($_POST["num_convidados"]) : 0;
        $mediaConsumo = calcularMediaConsumo($quantidades, $numConvidados);
    }

    // Função para calcular o valor de um item
    function calcularValorItem($quantidade, $unidade)
    {
        // Defina um valor para cada unidade (1 kg = R$10, 1 litro = R$5, outros = R$2)
        $valorPorUnidade = [
            "kg" => 10,
            "litros" => 5,
            "unidade" => 2
        ];

        // Use o valor correspondente à unidade do item
        return $quantidade * $valorPorUnidade[$unidade];
    }

    // Função para calcular a média de consumo por convidado
    function calcularMediaConsumo($quantidades, $numConvidados)
    {
        $mediaConsumo = [];
        foreach ($quantidades as $item => $info) {
            if ($info["unidade"] === "litros") {
                $mediaConsumo[$item] = $numConvidados > 0 ? $info["quantidade"] / $numConvidados : 0;
            } else {
                $mediaConsumo[$item] = 0;
            }
        }
        return $mediaConsumo;
    }
    ?>

    <form method="post" action="index.php">
        <section>
            <h2>Escolha os Itens do Menu</h2>
            <ul>
                <?php
                // Exibir itens da lista de desejos com campos de entrada
                foreach ($itens as $item => $info) {
                    echo "<li>{$info['nome']}: <input type='number' name='$item' value='0' min='0'></li>";
                }
                ?>
            </ul>

            <!-- Adicione um campo para o usuário inserir o número de convidados -->
            <label for="num_convidados">Número de Convidados:</label>
            <input type="number" name="num_convidados" value="0" min="0">

            <input type="submit" value="Calcular">
        </section>
    </form>

    <?php if (!empty($mediaConsumo)) : ?>
        <section>
            <h2>Lista de Desejos</h2>
            <ul>
                <?php
                // Exibir itens da lista de desejos
                foreach ($quantidades as $item => $info) {
                    echo "<li>{$itens[$item]['nome']}: {$info['quantidade']} {$info['unidade']} (R$ {$info['valor']})</li>";
                }
                ?>
            </ul>
        </section>

        <section>
            <h2>Média de Consumo por Convidado</h2>
            <ul>
                <?php
                // Exibir média de consumo por convidado
                foreach ($mediaConsumo as $item => $media) {
                    echo "<li>{$itens[$item]['nome']}: $media {$itens[$item]['unidade']}</li>";
                }
                ?>
            </ul>
        </section>

        <section>
            <h2>Total do Valor do Churrasco</h2>
            <p>R$ <?php echo number_format($totalValor, 2, ',', '.'); ?></p>
        </section>
    <?php endif; ?>

    <section>
        <h2>Informações do Churrasco</h2>
        <p>Número de Convidados: <?php echo $numConvidados; ?></p>
    </section>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Enrique de Abreu Dutra</p>
    </footer>
</body>

</html>
