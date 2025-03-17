const menuToggle = document.querySelector('.menu-toggle');
const sidebar = document.querySelector('.sidebar');
const postagens = document.querySelector('.postagens');

menuToggle.addEventListener('click', () => {
    sidebar.classList.toggle('active');
});

function abrirPostagem(id) {
    postagens.style.display = 'block';
    const todasPostagens = document.querySelectorAll('.postagem');
    todasPostagens.forEach(post => {
        post.style.display = post.id === id ? 'block' : 'none';
    });
}
_elements.switch.addEventListener("click", () => {
    const isDark = _elements.switch.classList.toggle("switch__track--dark");
    if(isDark == true){
        document.documentElement.setAttribute("data-theme","dark");
    } else{
        document.documentElement.setAttribute("data-theme","modern");
    }
    
});

