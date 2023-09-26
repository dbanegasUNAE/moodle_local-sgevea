var showModal = document.getElementById("showModal");

if (showModal.value) {
    var modal = document.getElementById("myModal");
    modal.style.display = "block";
}

document.getElementById("closeModal").addEventListener("click", function () {
    var modal = document.getElementById("myModal");
    modal.style.display = "none";
});

window.addEventListener("click", function (event) {
    var modal = document.getElementById("myModal");
    if (event.target == modal) {
        modal.style.display = "none";
    }
});