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

    document.getElementById("formDieta").addEventListener("submit", function(e) {
        e.preventDefault();
      
        const titulo = document.getElementById("titulo").value.trim();
        const conteudo = document.querySelector(".ql-editor").innerHTML.trim();
      
        if (!titulo || !conteudo || conteudo === "<p><br></p>") {
          alert("Preencha todos os campos!");
          return;
        }
      
        fetch("publicar_artigos.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ titulo, conteudo }),
        })
        .then(res => res.json())
        .then(data => {
          if (data.sucesso) {
            alert("Dieta publicada com sucesso!");
            document.getElementById("formDieta").reset();
            document.querySelector(".ql-editor").innerHTML = "";
          } else {
            alert("Erro: " + data.mensagem);
          }
        });
      });
      
    
});

