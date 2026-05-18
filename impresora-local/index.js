const io = require("socket.io-client");
const fs = require("fs");
const path = require("path");
const { print } = require("pdf-to-printer");

const SOCKET_URL = "http://192.168.100.120:3000";

// Reemplaza por el nombre exacto de tu impresora en Windows | Microsoft Print to PDF
const PRINTER_NAME = "Microsoft Print to PDF";
//const PRINTER_NAME = "POS-80-Series";

const socket = io(SOCKET_URL);

socket.on("connect", () => {
    console.log("Conectado al servidor:", socket.id);
});

socket.on("disconnect", () => {
    console.log("Desconectado del servidor.");
});

socket.on("imprimir_ticket", async (data) => {
    try {
        if (!data.ticket) {
            console.log("No se recibió un ticket válido.");
            return;
        }

        //console.log("Ticket recibido:", data.ticket);

        const fecha = new Date().toLocaleString("es-PE");

        const contenido = `
SIS TURNOS
--------------------------------
TICKET: ${data.ticket}

Espere su turno, por favor
${fecha}
--------------------------------
`;

        // Guardar archivo temporal
        const archivo = path.join(__dirname, "ticket.txt");
        fs.writeFileSync(archivo, contenido, "utf8");

        //console.log("Enviando a la impresora...");

        // Enviar a la impresora de Windows
        await print(archivo, {
            printer: PRINTER_NAME
        });

        //console.log("Ticket enviado correctamente a la impresora.");
    } catch (error) {
        console.error("Error al imprimir:", error);
    }
});

/*
const io = require("socket.io-client");
const fs = require("fs");
const path = require("path");
const { print } = require("pdf-to-printer");

const SOCKET_URL = "http://192.168.100.120:3000";
const PRINTER_NAME = "POS-80-Series";

const socket = io(SOCKET_URL);

socket.on("connect", () => {
    console.log("Conectado al servidor:", socket.id);
});

socket.on("imprimir_ticket", async (data) => {
    try {
        const contenido = `
TICKET: ${data.ticket}
--------------------------
Espere su turno, por favor
${new Date().toLocaleString("es-PE")}
`;

        const archivo = path.join(__dirname, "ticket.txt");
        fs.writeFileSync(archivo, contenido, "utf8");

        console.log("Ticket recibido:", data.ticket);

        // Aquí luego podemos cambiar a ESC/POS para impresión térmica directa.
        console.log("Contenido listo para imprimir.");
    } catch (error) {
        console.error("Error al imprimir:", error);
    }
});

socket.on("disconnect", () => {
    console.log("Desconectado del servidor.");
});*/