// Función para cargar el header y footer desde archivos externos
function cargarComponentes() {
  fetch('../../Components/header.html')
  .then(response => response.text())
  .then(data => {
      document.querySelector('header').innerHTML = data;
  });
  
  fetch('../../Components/footer.html')
  .then(response => response.text())
  .then(data => {
      document.querySelector('footer').innerHTML = data;
  });
}

document.addEventListener('DOMContentLoaded', cargarComponentes);

