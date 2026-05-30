const io = require("socket.io-client");
const fs = require("fs");
const path = require("path");
const PDFDocument = require("pdfkit");
const { print } = require("pdf-to-printer");

//const SOCKET_URL = "http://192.168.0.6:3000";
const SOCKET_URL = "http://192.168.100.120:3000";
const PRINTER_NAME = "POS-80-Series";

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

        const fecha = new Date().toLocaleString("es-PE");

        // Archivo PDF temporal
        const archivo = path.join(__dirname, "ticket.pdf");

        // Tamaño del ticket: 80 mm de ancho
        // 80 mm ≈ 226.77 puntos PDF
        const doc = new PDFDocument({
            size: [226.77, 1000], // altura grande temporal
            margins: {
                top: 10,
                bottom: 10,
                left: 10,
                right: 10
            }
        });

        const stream = fs.createWriteStream(archivo);
        doc.pipe(stream);

        // Encabezado
        doc
            .font("Helvetica-Bold")
            .fontSize(18)
            .text("EPS ILO S.A.", {
                align: "center"
            });

        doc.moveDown(0.3);

        doc
            .font("Helvetica")
            .fontSize(10)
            .text("--------------------------------", {
                align: "center"
            });

        doc.moveDown(0.5);

        // Ticket grande y centrado
        doc
            .font("Helvetica-Bold")
            .fontSize(34)
            .text(data.ticket, {
                align: "center"
            });

        doc.moveDown(0.5);

        doc
            .font("Helvetica")
            .fontSize(14)
            .text("Espere su turno, por favor", {
                align: "center"
            });

        doc.moveDown(0.3);

        doc
            .fontSize(12)
            .text(fecha, {
                align: "center"
            });

        doc.moveDown(0.3);

        doc
            .fontSize(10)
            .text("--------------------------------", {
                align: "center"
            });
        
        doc.page.height = doc.y + 10;

        doc.end();

        // Esperar a que el PDF termine de generarse
        await new Promise((resolve, reject) => {
            stream.on("finish", resolve);
            stream.on("error", reject);
        });

        // Imprimir
        await print(archivo, {
            printer: PRINTER_NAME
        });

        console.log(`Ticket ${data.ticket} enviado a impresión.`);
    } catch (error) {
        console.error("Error al imprimir:", error);
    }
});

/*
const io = require("socket.io-client");
const fs = require("fs");
const path = require("path");
const { print } = require("pdf-to-printer");

const SOCKET_URL = "http://192.168.0.6:3000";

// Reemplaza por el nombre exacto de tu impresora en Windows | Microsoft Print to PDF
//const PRINTER_NAME = "EPSILOP25010 (HP MFP 4103)";
const PRINTER_NAME = "POS-80-Series";

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
});*/

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