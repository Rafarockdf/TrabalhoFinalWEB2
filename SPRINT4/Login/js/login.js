const _elements = {
  loading: document.querySelector(".loading"),
  switch: document.querySelector(".switch__track"),
  menuToggle: document.querySelector('.menu-toggle'),
  sidebar: document.querySelector('.sidebar'),
  container: document.querySelector('.container')
}

_elements.switch.addEventListener("click", () => {
  const isDark = _elements.switch.classList.toggle("switch__track--dark");
  if(isDark == true){
      document.documentElement.setAttribute("data-theme","dark");
  } else{
      document.documentElement.setAttribute("data-theme","modern");
  }
  
});

_elements.menuToggle.addEventListener('click', () => {
  _elements.sidebar.classList.toggle('collapsed');
  _elements.menuToggle.classList.toggle('collapsed');
  _elements.container.classList.toggle('collapsed');
});