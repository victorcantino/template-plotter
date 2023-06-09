window.onload = function () {
    const material = document.getElementById("material");
    const superficie = document.getElementById("superficie");
    const tictac_frente = document.getElementById("tictac_frente");
    const tictac_verso = document.getElementById("tictac_verso");
    const frente = document.getElementById("frente");
    const verso = document.getElementById("verso");
    material.onchange = function () {
        frente.setAttribute('disabled', true);
        verso.setAttribute('disabled', true);
        tictac_frente.setAttribute('disabled', true);
        tictac_verso.setAttribute('disabled', true);
        if (material.value === 'Cordão/Tirante com tic-tac') {
            if (superficie.value === 'Frente e Verso iguais' || superficie.value === 'Apenas Frente') {
                frente.removeAttribute('disabled');
                tictac_frente.removeAttribute('disabled');
            } else if (superficie.value === 'Frente e Verso diferentes') {
                frente.removeAttribute('disabled');
                verso.removeAttribute('disabled');
                tictac_frente.removeAttribute('disabled');
                tictac_verso.removeAttribute('disabled');
            }
        } else if (material.value !== '') {
            if (superficie.value === 'Frente e Verso iguais' || superficie.value === 'Apenas Frente') {
                frente.removeAttribute('disabled');
            } else if (superficie.value === 'Frente e Verso diferentes') {
                frente.removeAttribute('disabled');
                verso.removeAttribute('disabled');
            }
        }
    };
    superficie.onchange = material.onchange;
};