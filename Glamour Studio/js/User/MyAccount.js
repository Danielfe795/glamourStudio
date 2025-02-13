  
  // Función para mostrar y ocultar secciones
  function showContent(section) {
      // Ocultar todas las secciones
      var sections = document.querySelectorAll('.content-section');
      sections.forEach(function(sec) {  // Cambio de variable para evitar confusión con el parámetro
          sec.style.display = 'none';
      });
  
      // Mostrar la sección seleccionada
      document.getElementById(section).style.display = 'block';
  
      // Si la sección es "salir", mostrar mensaje y redirigir
      if (section === 'salir') {
          mostrarMensajeSalir();
      }
  }
  
  // Función que se ejecuta cuando el usuario cierra sesión
  function mostrarMensajeSalir() {
      var salirDiv = document.getElementById('salir');
      salirDiv.style.display = 'block';  // Mostrar el mensaje de despedida
  
      // Redirigir al login después de 2 segundos (2000 ms)
      setTimeout(function() {
          window.location.href = '../../index.php';  // Asegúrate de que la ruta sea correcta
      }, 2000); // Puedes ajustar el tiempo si prefieres que sea más o menos
  }
  