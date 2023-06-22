<!DOCTYPE html>
<html>

<head>
    <script src="script.js"></script>
    <title>Gerar arquivo de impressão</title>
</head>

<body>
    <h1>Gerar arquivo de impressão</h1>

    <form action="salva.php" method="POST" enctype="multipart/form-data">

        <label for="material">Material:</label>
        <select id="material" name="material">
            <option value=""></option>
            <?php foreach (MATERIAIS as $material) : ?>
                <option value="<?= $material ?>"><?= $material ?></option>
            <?php endforeach; ?>
        </select>
        <label for="largura">Largura:</label>
        <select id="largura" name="largura">
            <option value=""></option>
            <?php foreach (LARGURA_MATERIAL as $largura) : ?>
                <option value="<?= $largura; ?>"><?= $largura; ?>mm</option>
            <?php endforeach; ?>
        </select>
        <label for="comprimento">Comprimento:</label>
        <select id="comprimento" name="comprimento">
            <option value=""></option>
            <?php foreach (COMPRIMENTO_MATERIAL as $comprimento) : ?>
                <option value="<?= $comprimento ?>"><?= $comprimento ?>mm</option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="quantidade">Quantidade necessária:</label>
        <input type="number" id="quantidade" name="quantidade" min="1"><br><br>


        <label for="superficie">Aplicação da arte:</label>
        <select id="superficie" name="superficie">
            <option value=""></option>
            <?php foreach (SUPERFICIES as $superficie) : ?>
                <option value="<?= $superficie ?>"><?= $superficie ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="frente">Arquivo da frente:</label>
        <input type="file" id="frente" name="frente" accept=".png"><br><br>

        <label for="verso">Arquivo da verso:</label>
        <input type="file" id="verso" name="verso" accept=".png"><br><br>

        <label for="tictac_frente">Arquivo da da frente do tic-tac:</label>
        <input type="file" id="tictac_frente" name="tictac_frente" accept=".png"><br><br>

        <label for="tictac_verso">Arquivo da dp verso do tic-tac:</label>
        <input type="file" id="tictac_verso" name="tictac_verso" accept=".png"><br><br>


        <label for="os">OS:</label>
        <input type="text" id="os" name="os">
        <label for="projeto">Projeto:</label>
        <input type="text" id="projeto" name="projeto">
        <label for="modelo">Modelo:</label>
        <input type="text" id="modelo" name="modelo">
        <label for="observacao">Observação:</label>
        <input type="text" id="observacao" name="observacao"><br><br>

        <input type="submit" value="Enviar">
    </form>
</body>

</html>