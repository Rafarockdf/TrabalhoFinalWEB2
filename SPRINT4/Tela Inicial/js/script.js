// script.js (usado em TelaInicial.html)

console.log("SCRIPT CARREGADO");

// Seleciona elementos globais
const _elements = {
    loading: document.querySelector(".loading"),
    switch: document.querySelector(".switch__track"),
    menuToggle: document.querySelector('.menu-toggle'),
    sidebar: document.querySelector('.sidebar'),
    container: document.querySelector('.container')
};

console.log('_elements encontrados:', _elements);

// Toggle do menu lateral
if (_elements.menuToggle && _elements.sidebar && _elements.container) {
    _elements.menuToggle.addEventListener('click', () => {
        console.log('Menu toggle clicado');
        _elements.sidebar.classList.toggle('collapsed');
        _elements.menuToggle.classList.toggle('collapsed');
        _elements.container.classList.toggle('collapsed');
    });
} else {
    console.warn("Elementos do menu/sidebar não encontrados. O toggle não será configurado.");
}

// Alternância de tema
if (_elements.switch) {
    _elements.switch.addEventListener("click", () => {
        console.log('Switch de tema clicado');
        const isDark = _elements.switch.classList.toggle("switch__track--dark");
        document.documentElement.setAttribute("data-theme", isDark ? "dark" : "modern");
    });
} else {
    console.warn("Elemento do switch de tema não encontrado.");
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
