
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
    if(isDark == true){
        document.documentElement.setAttribute("data-theme","dark");
    } else{
        document.documentElement.setAttribute("data-theme","modern");
    }
    
});

menuToggle.addEventListener('click', () => {
    sidebar.classList.toggle('active');
});

function toggleCard(cardType) {
    const card = document.querySelector(`.${cardType}`);
    const options = document.getElementById(`${cardType}-options`);

    // Alterna a classe expanded nos cards
    card.classList.toggle('expanded');
    // Alterna a exibição das opções
    options.style.display = card.classList.contains('expanded') ? 'block' : 'none';
}

