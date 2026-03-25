function llamarTicket(){

    fetch("../../controlador/atencion/llamar_ticket.php", {
        method: "POST"
    })
    .then(response => response.text())
    .then(data => {

        if(data.trim() === "OK"){
            location.reload();
        }else{
            Swal.fire("Sin tickets", "No hay tickets disponibles", "warning");
        }

    });

}