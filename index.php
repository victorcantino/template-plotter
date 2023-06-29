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
        <select id="material" name="material" required>
            <option value=""></option>
            <option value="Chaveiro">Chaveiro</option>
            <option value="Pulseira de acesso">Pulseira de acesso</option>
            <option value="Cordão/Tirante">Cordão/Tirante</option>
            <option value="Cordão/Tirante com tic-tac">Cordão/Tirante com tic-tac</option>
        </select>
        <label for="largura">Largura:</label>
        <select id="largura" name="largura" required>
            <option value=""></option>
            <option value="15">15mm</option>
            <option value="20">20mm</option>
            <option value="25">25mm</option>
        </select>
        <label for="comprimento">Comprimento:</label>
        <select id="comprimento" name="comprimento" required>
            <option value=""></option>
            <option value="300">300mm</option>
            <option value="360">360mm</option>
            <option value="850">850mm</option>
            <option value="950">950mm</option>
            <option value="1020">1.020mm</option>
            <option value="1070">1.070mm</option>
            <option value="1200">1.200mm</option>
            <option value="1400">1.400mm</option>
        </select><br><br>

        <label for="quantidade">Quantidade necessária:</label>
        <input type="number" id="quantidade" name="quantidade" min="1" required><br><br>

        <label for="superficie">Aplicação da arte:</label>
        <select id="superficie" name="superficie" required>
            <option value=""></option>
            <option value="Apenas Frente">Apenas Frente</option>
            <option value="Frente e Verso iguais">Frente e Verso iguais</option>
            <option value="Frente e Verso diferentes">Frente e Verso diferentes</option>
        </select><br><br>

        <label for="cor_linha">Cor da linha:</label>
        <input type="color" name="cor_linha" id="cor_linha"><br><br>

        <label for="frente">Arquivo da frente:</label>
        <input type="file" id="frente" name="frente" accept=".png" required disabled><br><br>

        <label for="verso">Arquivo do verso:</label>
        <input type="file" id="verso" name="verso" accept=".png" required disabled><br><br>

        <label for="tictac_frente">Arquivo da frente do tic-tac:</label>
        <input type="file" id="tictac_frente" name="tictac_frente" accept=".png" required disabled><br><br>

        <label for="tictac_verso">Arquivo do verso do tic-tac:</label>
        <input type="file" id="tictac_verso" name="tictac_verso" accept=".png" required disabled><br><br>

        <label for="os">OS:</label>
        <input type="text" id="os" name="os" required>
        <label for="projeto">Projeto:</label>
        <input type="text" id="projeto" name="projeto" required>
        <label for="modelo">Modelo:</label>
        <input type="text" id="modelo" name="modelo" required>
        <label for="observacao">Observação:</label>
        <input type="text" id="observacao" name="observacao" required><br><br>

        <input type="submit" value="Enviar">
    </form>
</body>

</html>