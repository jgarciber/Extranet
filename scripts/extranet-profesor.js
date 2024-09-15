var formDarAltaIncidencia = document.getElementById('formDarAltaIncidencia');
var formActualizarIncidencia = document.getElementById('formActualizarIncidencia')
var defaultOpen = document.getElementById('defaultOpen');

if(formDarAltaIncidencia) formDarAltaIncidencia.addEventListener('submit', function(){document.getElementById('detallesIncidencia').value = document.getElementById('detallesIncidenciaTextArea').value;});
if(formActualizarIncidencia) formActualizarIncidencia.addEventListener('submit', function(){document.getElementById('nuevosDetallesIncidencia').value = document.getElementById('nuevosDetallesIncidenciaTextArea').value;});

// Para abrir una pestaña específica al cargar la página, utilizo JavaScript para "hacer clic" en el botón de pestaña especificado:
// Obtengo el elemento id="defaultOpen" y hago click sobre él.
if(defaultOpen) defaultOpen.click();

// document.getElementById('ver-notas').addEventListener('click', function(){document.getElementById("modalVerNotasAlumno").click();});


function smoothScrollJS(idDestino){
   document.getElementById(idDestino).scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function mostrarMensajeEnCursor(mensajeAMostrar){
   var myDiv = '<div id="mensajesAJAXCursor">';
   var fadeDelay = 2000;
   $(document).mousemove(function(e){
      var divMensajes = $(myDiv)
         .css({
            "left": e.pageX + 'px',
            "top": e.pageY + 'px'
         })
         .append(mensajeAMostrar)
         .appendTo(document.body);
         $(document).off("mousemove");

      setTimeout(function() { 
         divMensajes.fadeOut("slow", function() { $(this).remove(); });
      }, fadeDelay);
   });
}

function mostrarMensajeEsquina(mensajeAMostrar){
   var myDiv = '<div id="mensajesAJAXEsquina">';
   var fadeDelay = 2000;
   var divMensajes = $(myDiv).append(mensajeAMostrar).appendTo(document.body);
   
   setTimeout(function() { 
      divMensajes.fadeOut("slow", function() { $(this).remove(); });
   }, fadeDelay);
}

function mostrarToastEsquina(mensajeAMostrar){
   var fadeDelay = 3000;
   var myDiv = `<div class="toast" data-delay="${fadeDelay}" style="position: fixed; right: 30px; bottom: 30px;">
      <div class="toast-header">
         <strong class="mr-auto">Notificación Extranet</strong>
         <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
         </button>
      </div>
      <div class="toast-body">
         ${mensajeAMostrar}
      </div>
      </div>`;
   var divMensajes = $(myDiv).appendTo(document.body);
   $('.toast').toast('show');
   setTimeout(function() { divMensajes.remove();}, fadeDelay+1000);
}