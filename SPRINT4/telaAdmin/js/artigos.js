document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("form-artigo");

  form.addEventListener("submit", function (e) {
      e.preventDefault();

      const titulo = document.getElementById("titulo").value.trim();
      const conteudo = document.getElementById("conteudo").value.trim();
      const autor = document.getElementById("autor").value.trim();

      if (!titulo || !conteudo || !autor) {
          alert("Preencha todos os campos!");
          return;
      }

      const formData = new FormData();
      formData.append("titulo", titulo);
      formData.append("conteudo", conteudo);
      formData.append("autor", autor);

      fetch("../php/publicar_artigos.php", {
          method: "POST",
          body: formData
      })
      .then(res => res.json())
      .then(data => {
          if (data.sucesso) {
              alert("Artigo publicado com sucesso!");
              form.reset();
          } else {
              alert("Erro: " + data.mensagem);
          }
      })
      .catch(err => {
          alert("Erro de conexão com o servidor.");
          console.error(err);
      });
  });
});

document.getElementById('formArtigo').addEventListener('submit', async function (e) {
  e.preventDefault();

  const form = e.target;
  const formData = new FormData(form);

  try {
      const response = await fetch('php/publicar_artigos.php', {
          method: 'POST',
          body: formData
      });

      const resultado = await response.json();

      if (resultado.sucesso) {
          alert("Artigo publicado com sucesso!");
          form.reset(); // limpa o formulário após sucesso
      } else {
          alert("Erro: " + resultado.mensagem);
      }
  } catch (error) {
      console.error("Erro na requisição:", error);
      alert("Erro inesperado ao tentar publicar o artigo.");
  }
});
