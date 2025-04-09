
const _elements = {
    switch: document.querySelector(".switch__track"),
    container: document.querySelector('.container'),
    menuToggle: document.querySelector('.menu-toggle'),
    sidebar: document.querySelector('.sidebar'),
    postagens: document.querySelector('.postagens')
}


_elements.menuToggle.addEventListener('click', () => {
    _elements.sidebar.classList.toggle('collapsed');
    _elements.menuToggle.classList.toggle('collapsed');
    _elements.container.classList.toggle('collapsed');
});

_elements.switch.addEventListener("click", () => {
    const isDark = _elements.switch.classList.toggle("switch__track--dark");
    if (isDark == true) {
        document.documentElement.setAttribute("data-theme", "dark");
    } else {
        document.documentElement.setAttribute("data-theme", "modern");
    }

});

function mostrarCalculadora(event) {
    event.preventDefault(); // Impede o envio do formulário
    document.getElementById('calculadoraSentimentos').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
}

function submitEmotion(emocao) {
    document.getElementById('emocaoInput').value = emocao;
    document.getElementById('emotionForm').submit();
}

menuToggle.addEventListener('click', () => {
    sidebar.classList.toggle('active');
});