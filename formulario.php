<!DOCTYPE html>
<html>

<head>
    <title>Gerar arquivo de impressão</title>
</head>

<body>
    <h1>Gerar arquivo de impressão</h1>

    <form action="template.php" method="POST" enctype="multipart/form-data">
        <label for="frente">Arquivo da frente do material:</label>
        <input type="file" id="frente" name="frente" accept=".png"><br><br>

        <label for="verso">Arquivo do verso do material:</label>
        <input type="file" id="verso" name="verso" accept=".png"><br><br>

        <label for="tictac_frente">Arquivo do tictac frente do material:</label>
        <input type="file" id="tictac_frente" name="tictac_frente" accept=".png"><br><br>

        <label for="tictac_verso">Arquivo do tictac verso do material:</label>
        <input type="file" id="tictac_verso" name="tictac_verso" accept=".png"><br><br>

        <label for="largura">Largura do material:</label>
        <select id="largura" name="largura">
            <option value="15">15mm</option>
            <option value="20">20mm</option>
            <option value="25">25mm</option>
        </select><br><br>

        <label for="comprimento">Comprimento do material:</label>
        <select id="comprimento" name="comprimento">
            <option value="300">300mm</option>
            <option value="370">370mm</option>
            <option value="720">720mm</option>
            <option value="850">850mm</option>
            <option value="950">950mm</option>
            <option value="1071">1071mm</option>
            <option value="1200">1200mm</option>
            <option value="1400">1400mm</option>
        </select><br><br>

        <label for="quantidade">Quantidade necessária:</label>
        <input type="number" id="quantidade" name="quantidade" min="1"><br><br>

        <label for="os">Número da OS:</label>
        <input type="text" id="os" name="os"><br><br>

        <label for="projeto">Nome do Projeto:</label>
        <input type="text" id="projeto" name="projeto"><br><br>

        <label for="modelo">Modelo do Projeto:</label>
        <input type="text" id="modelo" name="modelo"><br><br>

        <label for="observacao">Observação:</label>
        <input type="text" id="observacao" name="observacao"><br><br>

        <input type="submit" value="Enviar">
    </form>
</body>

</html>