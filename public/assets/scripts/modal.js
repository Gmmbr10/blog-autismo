const modals = document.querySelectorAll(".modal");

for (const modal of modals) {
  modal.querySelector(".modal__close").onclick = function () {
    modal.close();
  };
}


function abrir_modal(idModal)
{
    const modal = "#" + idModal + ".modal";
    document.querySelector(modal).showModal();
}