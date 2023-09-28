document.addEventListener('DOMContentLoaded', function () {
    // Para el botón de recarga
    var reloadButton = document.querySelector('.btnAccReload');
    if (reloadButton) {
        reloadButton.addEventListener('click', function () {
            window.location.reload();
        });
    }

    // Para el botón de imprimir
    var printButton = document.querySelector('.btnAccPrint');
    if (printButton) {
        printButton.addEventListener('click', function () {
            window.print();
        });
    }
});
