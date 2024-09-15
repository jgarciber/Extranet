function openMenu(evt, menuName) {
   // Declaro todas las variables
   var i, tabcontent, tablinks;

   // Obtengo todos los elementos de class="tabcontent" y los oculto
   tabcontent = document.getElementsByClassName("tabcontent");
   for (i=0; i<tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
   }

   // Obtengo todos los elemenots de class="tablinks" y elimino la clase "activa"
   tablinks = document.getElementsByClassName("tablinks");
   for (i=0; i<tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
   }

   // Muestro la pestaña actual y añado la clase "activa" al botón que abrio dicha pestaña.
   document.getElementById(menuName).style.display = "block";
   evt.currentTarget.className += " active";
}
