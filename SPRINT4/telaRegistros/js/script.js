
const _elements = {
    switch: document.querySelector(".switch__track"),
    container: document.querySelector('.container'),
    menuToggle: document.querySelector('.menu-toggle'),
    sidebar: document.querySelector('.sidebar'),
    postagens: document.querySelector('.postagens'),
    calculadoraEmocional: document.querySelector('.calculadoraSentimentos')
}


_elements.menuToggle.addEventListener('click', () => {
    _elements.sidebar.classList.toggle('collapsed');
    _elements.menuToggle.classList.toggle('collapsed');
    _elements.container.classList.toggle('collapsed');
    _elements.calculadoraEmocional.classList.toggle('collapsed')
});

_elements.switch.addEventListener("click", () => {
    const isDark = _elements.switch.classList.toggle("switch__track--dark");
    if (isDark == true) {
        document.documentElement.setAttribute("data-theme", "dark");
    } else {
        document.documentElement.setAttribute("data-theme", "modern");
    }

});

let atividadeSelecionada = null; // variável global para guardar qual atividade foi clicada

function abrirCalculadora(atividadeId) {
    atividadeSelecionada = atividadeId; // guarda o id da atividade
    document.getElementById('calculadoraSentimentos').style.display = 'block';
}

function submitEmotion(emocao) {
    if (!atividadeSelecionada) {
        alert('Nenhuma atividade selecionada!');
        return;
    }

    // Envia para o PHP
    fetch('../php/registrar_emocao.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'atividade_id=' + atividadeSelecionada + '&emocao=' + encodeURIComponent(emocao)
    })
    .then(response => response.text())
    .then(data => {
        if (data.trim() === 'success') {
            alert('Atividade e humor registrados com sucesso!');
            location.reload(); // recarrega para atualizar status
        } else {
            console.error('Erro:', data);
            alert('Erro ao registrar emoção!');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro na conexão com o servidor.');
    });
}


menuToggle.addEventListener('click', () => {
    sidebar.classList.toggle('active');
});


