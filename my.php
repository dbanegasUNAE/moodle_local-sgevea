<?php 
//$PAGE->requires->jquery_plugin('ui');

// Obtén la URL de la encuesta externa que deseas cargar en el iframe
$externalSurveyUrl = null;//"https://sgevea.unae.edu.ec/";
$externalSurveyUrl ="https://sgevea.unae.edu.ec/admin/api/surveys/def502007c262effc74ff4a0bd15b99572c02184355147449e53b472c460e70a44edcc9b1c9d6a341c987449cd0f7c02797d1e340b25e3e92a75f82cabf9340b44cd2ccdc841a1ad2489cfacd86c59ab38c572a077";

// Define el contenido del modal
$modalContent = html_writer::start_tag('div', array('class' => 'modal-content'));

// Agrega un botón para cerrar el modal en la esquina superior derecha
$modalContent .= html_writer::tag('button', '&times;', array(
    'class' => 'close-modal',
    'id' => 'closeModal',
));

// Agrega el iframe con la URL de la encuesta externa solo si existe una URL válida
if (!empty($externalSurveyUrl)) {
    $modalContent .= html_writer::tag('iframe', '', array(
        'src' => $externalSurveyUrl,
        'frameborder' => '0',
        'class' => 'modal-iframe',
    ));
} else {
    // Si no hay URL disponible, muestra un mensaje informativo en lugar del iframe.
    $modalContent .= get_string('nosurveyavailable', 'local_myplugin');
}

$modalContent .= html_writer::end_tag('div');

// Imprime el modal directamente sin el botón
echo html_writer::start_tag('div', array('class' => 'modal', 'id' => 'myModal'));
echo $modalContent;
echo html_writer::end_tag('div');


// Agrega JavaScript para abrir y cerrar el modal automáticamente si la URL no está vacía
echo '<script>
if (' . json_encode(!empty($externalSurveyUrl)) . ') {
    var modal = document.getElementById("myModal");
    modal.style.display = "block";
}

document.getElementById("closeModal").addEventListener("click", function() {
    var modal = document.getElementById("myModal");
    modal.style.display = "none";
});

window.addEventListener("click", function(event) {
    var modal = document.getElementById("myModal");
    if (event.target == modal) {
        modal.style.display = "none";
    }
});
</script>';

// Agrega estilos CSS para el modal y el iframe
echo '<style>
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.7);
    z-index: 9999;
}

.modal-content {
    position: absolute;
    top: 5%;
    left: 5%;
    width: 90%;
    height: 90%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.modal-iframe {
    width: 100%;
    height: 100%;
    border: none;
}

.close-modal {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 24px;
    color: #000;
    background: transparent;
    border: none;
    cursor: pointer;
}
</style>';