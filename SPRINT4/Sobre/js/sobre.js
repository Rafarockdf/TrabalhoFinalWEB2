const _elements = {
    loading: document.querySelector(".loading"),
    switch: document.querySelector(".switch__track"),
    menuToggle: document.querySelector('.menu-toggle'),
    sidebar: document.querySelector('.sidebar'),
    container: document.querySelector('.container')
}

_elements.switch.addEventListener("click", () => {
    const isDark = _elements.switch.classList.toggle("switch__track--dark");
    if (isDark == true) {
        document.documentElement.setAttribute("data-theme", "dark");
    } else {
        document.documentElement.setAttribute("data-theme", "modern");
    }

});

_elements.menuToggle.addEventListener('click', () => {
    _elements.sidebar.classList.toggle('collapsed');
    _elements.menuToggle.classList.toggle('collapsed');
    _elements.container.classList.toggle('collapsed');
});

document.addEventListener('DOMContentLoaded', function () {
    const checkpointListItems = document.querySelectorAll('.checkpoint-list li');
    const topOffset = 80;  // Ajuste este valor conforme necessário

    checkpointListItems.forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault(); // Impede o comportamento padrão do link (salto)
            checkpointListItems.forEach(li => li.classList.remove('active'));
            this.classList.add('active');

            const targetId = this.getAttribute('data-target');
            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                const targetPosition = targetElement.offsetTop - topOffset;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Adiciona classe 'active' ao primeiro item por padrão
    checkpointListItems[0].classList.add('active');
});

// --- Nova função para configurar o evento do ícone de perfil ---
function configurarEventoIconePerfil() {
    // Encontra o elemento do ícone de perfil (assumindo que tem a classe 'profile-icon')
    const profileIcon = document.querySelector('.profile-icon');

    // Verifica se o elemento foi encontrado
    if (profileIcon) {
        // Adiciona um ouvinte de evento de clique ao ícone
        profileIcon.addEventListener('click', () => {
            // Faz uma requisição para o script PHP que verifica o status da sessão
            // Ajuste o caminho '../php/verificar_sessao.php' conforme a localização do seu arquivo
            fetch('../../Verificar/verificar_sessao.php')
                .then(response => {
                    // Verifica se a resposta é OK antes de tentar parsear como JSON
                    if (!response.ok) {
                        // Se não for OK, lança um erro com o status
                        throw new Error(`Erro HTTP! Status: ${response.status}`);
                    }
                    return response.json(); // Tenta parsear a resposta como JSON
                })
                .then(data => {
                    // Com base na resposta do PHP
                    if (data.logado) {
                        // Se 'logado' for true, redireciona para a página de perfil
                        // Ajuste o caminho '../../Perfil/html/perfil.html' conforme a localização do seu arquivo
                        window.location.href = '../../Perfil/html/perfil.html';
                    } else {
                        // Se 'logado' for false, redireciona para a tela intermediária
                        // Ajuste o caminho '../../Tela Intermediaria/html/telaLoginCadastro.html' conforme a localização do seu arquivo
                        window.location.href = '../../Tela Intermediaria/html/telaLoginCadastro.html';
                    }
                })
                .catch(error => {
                    // Trata erros na requisição fetch (ex: script PHP não encontrado, erro de rede)
                    console.error('Erro ao verificar sessão:', error);
                    // Opcional: Exibir um alerta ou redirecionar para uma página de erro em caso de falha na verificação
                    alert('Não foi possível verificar o status da sessão. Tente novamente.');
                    // Exemplo de redirecionamento em caso de erro:
                    // window.location.href = '../../Tela Inicial/html/TelaInicial.html';
                });
        });
    } else {
        console.warn("Elemento com classe 'profile-icon' não encontrado.");
    }
}