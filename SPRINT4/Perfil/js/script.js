document.addEventListener("DOMContentLoaded", () => {
    // Carrega os dados do usuário ao carregar a página de perfil
    carregarDadosUsuario();
    // Configura os outros eventos da página (inputs, botão salvar)
    configurarEventos();
    // Configura o evento de clique para o ícone de perfil
    configurarEventoIconePerfil(); // <-- Nova função adicionada
});

function carregarDadosUsuario() {
    // Função existente para carregar dados do usuário
    fetch("../php/obter_usuario.php")
        .then(res => {
            // Verifica se a resposta é OK (status 200-299)
            if (!res.ok) {
                // Se não for OK, lança um erro com o status
                throw new Error(`Erro HTTP! Status: ${res.status}`);
            }
            return res.json(); // Tenta parsear a resposta como JSON
        })
        .then(user => {
            if (user && !user.erro) {
                // Preenche os campos do formulário com os dados do usuário
                document.getElementById("nome").value = user.nome;
                document.getElementById("email").value = user.email;
                document.getElementById("data_nascimento").value = user.data_nascimento;
                document.getElementById("sexo").value = user.sexo;
                document.getElementById("altura").value = user.altura;
                document.getElementById("peso").value = user.peso;

                // Preenche os campos somente leitura
                document.getElementById("idade").textContent = `${user.idade} anos`;
                document.getElementById("imc").textContent = user.IMC;
                // Formata a data de criação
                document.getElementById("criado_em").textContent = new Date(user.criado_em).toLocaleDateString();
            } else {
                // Exibe um alerta se houver um erro reportado pelo PHP (ex: Usuário não autenticado)
                alert("Erro ao carregar dados: " + (user.erro || "Desconhecido"));
                 // Opcional: Redirecionar para a tela de login/cadastro se não autenticado
                 // window.location.href = '../../Tela Intermediaria/html/telaLoginCadastro.html';
            }
        })
        .catch(err => {
            console.error("Erro ao carregar dados ou conectar com o servidor:", err);
            // Exibe um alerta genérico em caso de erro na requisição ou conexão
            alert("Erro ao conectar com o servidor ou carregar dados.");
             // Opcional: Redirecionar para a tela de login/cadastro em caso de erro de conexão/autenticação
             // window.location.href = '../../Tela Intermediaria/html/telaLoginCadastro.html';
        });
}

function configurarEventos() {
    // Função existente para configurar eventos de input e botão salvar
    document.getElementById("peso").addEventListener("input", atualizarIMC);
    document.getElementById("altura").addEventListener("input", atualizarIMC);

    // Adiciona o ouvinte de evento para o botão salvar
    const saveButton = document.querySelector(".save-btn");
    if (saveButton) { // Verifica se o botão existe antes de adicionar o evento
       saveButton.addEventListener("click", salvarAlteracoes);
    }
}

function atualizarIMC() {
    // Função existente para calcular e exibir o IMC
    const peso = parseFloat(document.getElementById("peso").value);
    const altura = parseFloat(document.getElementById("altura").value);

    if (peso > 0 && altura > 0) {
        const imc = (peso / (altura * altura)).toFixed(2);
        document.getElementById("imc").textContent = imc;
    } else {
        // Limpa o campo IMC se os valores não forem válidos
        document.getElementById("imc").textContent = "-";
    }
}

function salvarAlteracoes() {
    // Função existente para salvar as alterações do perfil
    const dados = {
        nome: document.getElementById("nome").value,
        email: document.getElementById("email").value,
        // Inclui a senha apenas se o campo não estiver vazio
        senha: document.getElementById("senha").value,
        data_nascimento: document.getElementById("data_nascimento").value,
        sexo: document.getElementById("sexo").value,
        altura: parseFloat(document.getElementById("altura").value),
        peso: parseFloat(document.getElementById("peso").value)
    };

    fetch("../php/atualizar_usuarios.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(dados)
        })
        .then(res => {
             // Verifica se a resposta é OK antes de tentar parsear como JSON
             if (!res.ok) {
                 // Se não for OK, lança um erro com o status
                 throw new Error(`Erro HTTP! Status: ${res.status}`);
             }
             return res.json(); // Tenta parsear a resposta como JSON
        })
        .then(resp => {
            if (resp.status === "sucesso") {
                alert("Perfil atualizado com sucesso! ✅");
            } else {
                // Exibe a mensagem de erro retornada pelo PHP
                alert("Erro ao atualizar: " + resp.mensagem);
            }
        })
        .catch(err => {
            console.error("Erro ao enviar dados ou conectar com o servidor:", err);
            // Exibe um alerta genérico em caso de erro na requisição ou conexão
            alert("Erro ao enviar dados ou conectar com o servidor.");
        });
}

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
