/*
document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".form-generar-ticket").forEach(form => {

        form.addEventListener("submit", function (e) {

            e.preventDefault();

            const formData = new FormData(this);
            formData.append("generar_turno", "1");

            fetch(URL_GENERAR_TICKET, {
                method: "POST",
                body: formData
            })
                .then(response => response.text())
                .then(ticket => {

                    ticket = ticket.trim();

                    // Mostrar modal
                    Swal.fire({
                        icon: "success",
                        title: "TICKET GENERADO",
                        html: `
                            <div style="font-size: 3rem; font-weight: bold; color: #0d6efd;">
                                ${ticket}
                            </div>
                            <br>
                            <div style="font-size: 1.2rem; color: #6c757d;">
                                Espere su turno, por favor.
                            </div>
                        `,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        timer: 3000,
                        timerProgressBar: true
                    });

                    // Imprimir ticket
                    imprimirTicket(ticket);
                })
                .catch(error => {
                    console.error("Error:", error);

                    Swal.fire(
                        "Error",
                        "No se pudo generar el ticket.",
                        "error"
                    );
                });

        });

    });

});*/


document.addEventListener("submit", function (e) {

    if (e.target.classList.contains("form-generar-ticket")) {
        e.preventDefault();

        const formData = new FormData(e.target);
        formData.append("generar_turno", "1");

        fetch(URL_GENERAR_TICKET, {
            method: "POST",
            body: formData
        })
            .then(response => response.text())
            .then(ticket => {
                ticket = ticket.trim();

                // Mostrar modal
                Swal.fire({
                    icon: "success",
                    title: "TICKET GENERADO",
                    html: `
                            <div style="font-size: 3rem; font-weight: bold; color: #0d6efd;">
                                ${ticket}
                            </div>
                            <br>
                            <div style="font-size: 1.2rem; color: #6c757d;">
                                Espere su turno, por favor.
                            </div>
                        `,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                //imprimirTicket(ticket);
            });
    }

});

/*
function imprimirTicket(ticket) {
    const fecha = new Date().toLocaleString("es-PE");

    const contenido = `
        <html>
        <head>
            <title>Imprimir Ticket</title>
            <style>
                body {
                    font-family: monospace;
                    text-align: center;
                    width: 80mm;
                    margin: 0;
                    padding: 10px;
                }

                .empresa {
                    font-size: 18px;
                    font-weight: bold;
                }

                .ticket {
                    font-size: 42px;
                    font-weight: bold;
                    margin: 20px 0;
                }

                .mensaje {
                    font-size: 16px;
                    margin-top: 15px;
                }

                .fecha {
                    font-size: 12px;
                    margin-top: 10px;
                }

                .corte {
                    margin-top: 20px;
                }

                @media print {
                    @page {
                        margin: 0;
                        size: 80mm auto;
                    }

                    body {
                        margin: 0;
                        padding: 10px;
                    }
                }
            </style>
        </head>
        <body>
            <div class="empresa">
                SIS TURNOS
            </div>

            <hr>

            <div class="ticket">
                ${ticket}
            </div>

            <div class="mensaje">
                Espere su turno, por favor
            </div>

            <div class="fecha">
                ${fecha}
            </div>

            <div class="corte">
                ------------------------------
            </div>

            <script>
                // Esperar a que todo el contenido cargue
                window.onload = function () {
                    // Abrir automáticamente el cuadro de impresión
                    window.print();

                    // Cerrar la ventana después de imprimir
                    window.onafterprint = function () {
                        window.close();
                    };
                };
            <\/script>
        </body>
        </html>
    `;

    // Abrir una ventana muy pequeña y fuera de foco visual
    const ventana = window.open(
        "",
        "_blank",
        "width=1,height=1,left=-1000,top=-1000"
    );

    if (!ventana) {
        alert("El navegador bloqueó la ventana emergente. Permita pop-ups para imprimir automáticamente.");
        return;
    }

    ventana.document.open();
    ventana.document.write(contenido);
    ventana.document.close();
}*/


function imprimirTicket(ticket) {

    const fecha = new Date().toLocaleString("es-PE");

    const contenido = `
        <html>
        <head>
            <title>Imprimir Ticket</title>
            <style>
                body {
                    font-family: monospace;
                    text-align: center;
                    width: 80mm;
                    margin: 0;
                    padding: 10px;
                }

                .empresa {
                    font-size: 18px;
                    font-weight: bold;
                }

                .ticket {
                    font-size: 42px;
                    font-weight: bold;
                    margin: 20px 0;
                }

                .mensaje {
                    font-size: 16px;
                    margin-top: 15px;
                }

                .fecha {
                    font-size: 12px;
                    margin-top: 10px;
                }

                .corte {
                    margin-top: 20px;
                }

                @media print {
                    @page {
                        margin: 0;
                        size: 80mm auto;
                    }
                }
            </style>
        </head>
        <body onload="window.print(); window.close();">

            <div class="empresa">
                SIS TURNOS
            </div>

            <hr>

            <div class="ticket">
                ${ticket}
            </div>

            <div class="mensaje">
                Espere su turno, por favor
            </div>

            <div class="fecha">
                ${fecha}
            </div>

            <div class="corte">
                ------------------------------
            </div>

        </body>
        </html>
    `;

    const ventana = window.open('', '_blank', 'width=400,height=600');
    ventana.document.write(contenido);
    ventana.document.close();
}

/*
|--------------------------------------------------------------------------
| ACTUALIZAR SOLO EL CONTENIDO DE pantalla_seleccion.php
|--------------------------------------------------------------------------
| Se vuelve a cargar contenido_seleccion.php y se reemplaza
| únicamente el div #contenedorSeleccion.
|--------------------------------------------------------------------------


function actualizarSeleccion() {
    fetch("contenido_seleccion.php")
        .then(response => response.text())
        .then(html => {
            document.getElementById("contenedorSeleccion").innerHTML = html;
        })
        .catch(error => {
            console.error("Error al actualizar la selección:", error);
        });
}*/


function actualizarSeleccion() {
    fetch("contenido_seleccion.php")
        .then(response => response.text())
        .then(html => {
            document.getElementById("contenedorSeleccion").innerHTML = html;

            // IMPORTANTE: volver a enlazar eventos
            inicializarEventos();
        })
        .catch(error => {
            console.error("Error al actualizar:", error);
        });
}

const socket = io(SOCKET_URL);

socket.on("connect", function () {
    console.log("Conectado al WebSocket:", socket.id);
});

socket.on("actualizar_pantalla", function (data) {
    console.log("Evento recibido:", data);
    //location.reload();
    actualizarSeleccion();
});
