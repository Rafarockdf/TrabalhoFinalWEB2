
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
// Configura o clique do ícone de perfil
function configurarEventoIconePerfil() {
    const profileIcon = document.querySelector('.profile-icon');
    console.log('Ícone de perfil encontrado:', profileIcon);

    if (profileIcon) {
        profileIcon.addEventListener('click', (event) => {
            console.log('Ícone de perfil clicado', event.target);

            if (_elements.loading) {
                _elements.loading.classList.remove('loading--hide');
            }

            // Verifica a sessão do usuário
            fetch('../../Verificar/verificar_sessao.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Erro HTTP! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (_elements.loading) {
                        _elements.loading.classList.add('loading--hide');
                    }

                    if (data.logado) {
                        console.log('Usuário logado. Redirecionando para perfil...');
                        window.location.href = '../../Perfil/html/perfil.html';
                    } else {
                        console.log('Usuário não logado. Redirecionando para tela intermediária...');
                        window.location.href = '../../Tela Intermediaria/html/telaLoginCadastro.html';
                    }
                })
                .catch(error => {
                    console.error('Erro ao verificar sessão:', error);
                    if (_elements.loading) {
                        _elements.loading.classList.add('loading--hide');
                    }
                    alert('Não foi possível verificar o status da sessão. Tente novamente.');
                });
        });
    } else {
        console.warn("Elemento '.profile-icon' não encontrado.");
    }
}

// Aguarda o carregamento do DOM
document.addEventListener("DOMContentLoaded", () => {
    console.log("DOM totalmente carregado. Inicializando...");
    configurarEventoIconePerfil();
});