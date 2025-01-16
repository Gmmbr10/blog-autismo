function createModal(type,msg,redirect)
    {

      let modal = '<dialog id="msg_modal" class="modal_msg">';

      if ( type == true ) {
        modal += '<i class="fa-solid fa-circle-check"></i>';
      } else {
        modal += '<i style="color: var(--error-font)" class="fa-solid fa-triangle-exclamation"></i>';
      }

      modal += '<section class="modal_msg__body">'+ msg +'</section></dialog>';

      document.body.innerHTML += modal; 

      document.getElementById("msg_modal").showModal();

      setTimeout(()=>{
        window.location.href = redirect;
      },3000);

    }