document.addEventListener("DOMContentLoaded", () => {
    // Troca de tema
    const switchTrack = document.querySelector(".switch__track");
    switchTrack.addEventListener("click", () => {
        const isDark = switchTrack.classList.toggle("switch__track--dark");
        document.documentElement.setAttribute("data-theme", isDark ? "dark" : "modern");
    });

    // Menu toggle
    const menuToggle = document.querySelector(".menu-toggle");
    const sidebar = document.querySelector(".sidebar");
    const container = document.querySelector(".container");
    menuToggle.addEventListener("click", () => {
        sidebar.classList.toggle("collapsed");
        menuToggle.classList.toggle("collapsed");
        container.classList.toggle("collapsed");
    });

    // Carregar usuários
    fetch("../php/listar_usuarios.php")
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector("#usuarios-table tbody");
            tbody.innerHTML = "";

            data.forEach(usuario => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${usuario.id}</td>
                    <td>${usuario.nome}</td>
                    <td>${usuario.email}</td>
                    <td>${usuario.genero}</td>
                    <td>${usuario.IMC}</td>
                    <td>
                        <button class="edit-btn" onclick="abrirModal(${usuario.id}, '${usuario.nome}', '${usuario.email}', '${usuario.genero}', '${usuario.IMC}')">Editar</button>
                        <button class="delete-btn" onclick="excluirUsuario(${usuario.id})">Excluir</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        });

    // Fechar o modal
    document.getElementById("closeModal").addEventListener("click", () => {
        document.getElementById("editModal").style.display = "none";
    });

    // Enviar edição
    document.getElementById("editUserForm").addEventListener("submit", function (e) {
        e.preventDefault();
    
        const data = {
            id: document.getElementById("editId").value,
            nome: document.getElementById("editNome").value,
            email: document.getElementById("editEmail").value,
            genero: document.getElementById("editGenero").value,
            imc: document.getElementById("editIMC").value
        };
    
        fetch("../php/editar_usuario.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(data => {
            if (data.sucesso) {
                alert("Usuário atualizado com sucesso!");
                location.reload();
            } else {
                alert("Erro ao atualizar: " + data.mensagem);
            }
        })
        .catch(err => {
            alert("Erro ao enviar requisição: " + err);
        });
    });
    
    
});

// Abre o modal e preenche os campos
function abrirModal(id, nome, email, genero, imc) {
    document.getElementById("editId").value = id;
    document.getElementById("editNome").value = nome;
    document.getElementById("editEmail").value = email;
    document.getElementById("editGenero").value = genero;
    document.getElementById("editIMC").value = imc;
    document.getElementById("editModal").style.display = "flex";
}

// Excluir usuário
function excluirUsuario(id) {
    if (confirm("Tem certeza que deseja excluir este usuário?")) {
        fetch(`../php/excluir_usuario.php?id=${id}`)
            .then(res => res.text())
            .then(msg => {
                alert("Usuário excluído com sucesso!");
                location.reload();
            });
    }
}
