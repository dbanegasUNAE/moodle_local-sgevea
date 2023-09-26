<?php 
require_once(__DIR__ . '/../../../config.php');
require_login();

// Establece la URL externa que deseas cargar en el iframe
$externalurl = "https://www.ejemplo.com/tu-url-externa";

// Define el contenido del modal
$content = html_writer::start_tag('div', array('class' => 'modal-content'));
$content .= html_writer::empty_tag('span', array('class' => 'modal-close', 'id' => 'modal-close', 'onclick' => 'closeModal();'), '&times;');
$content .= html_writer::tag('iframe', '', array('src' => $externalurl, 'frameborder' => '0'));
$content .= html_writer::end_tag('div');

// Genera el HTML del modal
echo html_writer::start_tag('div', array('class' => 'modal', 'id' => 'myModal'));
echo $content;
echo html_writer::end_tag('div');

// Agrega JavaScript para abrir y cerrar el modal
echo '<script>
function openModal() {
    document.getElementById("myModal").style.display = "block";
}

function closeModal() {
    document.getElementById("myModal").style.display = "none";
}

// Cierra el modal haciendo clic en la "X" o en cualquier área fuera del modal
document.getElementById("modal-close").addEventListener("click", function() {
    closeModal();
});

window.addEventListener("click", function(event) {
    if (event.target == document.getElementById("myModal")) {
        closeModal();
    }
});

// Abre el modal cuando se carga la página
document.addEventListener("DOMContentLoaded", function() {
    openModal();
});
</script>';
