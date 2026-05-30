const http = require('http');
const { Server } = require('socket.io');

const PORT = 3000;
const HOST = '0.0.0.0'; // Escuchar en todas las interfaces de red

const server = http.createServer((req, res) => {
    if (req.method === 'POST' && req.url === '/notificar') {
        let body = '';

        req.on('data', chunk => {
            body += chunk;
        });

        req.on('end', () => {
            let data = {};

            try {
                data = JSON.parse(body);
            } catch (e) {
                console.error('Error al parsear JSON:', e);
            }

            // Notificar a todos los clientes conectados
            io.emit('actualizar_pantalla', data);

            // Imprimir ticket solo si existe
            if (data.ticket) {
                io.emit('imprimir_ticket', data);
            }

            res.writeHead(200, { 'Content-Type': 'application/json' });
            res.end(JSON.stringify({ ok: true }));
        });

        return;
    }

    // Ruta de prueba para verificar conectividad
    if (req.method === 'GET' && req.url === '/') {
        res.writeHead(200, { 'Content-Type': 'text/plain' });
        res.end('Servidor Socket.IO funcionando correctamente');
        return;
    }

    res.writeHead(404);
    res.end();
});

const io = new Server(server, {
    cors: {
        origin: '*'
    }
});

io.on('connection', socket => {
    console.log('Cliente conectado:', socket.id);

    socket.on('disconnect', () => {
        console.log('Cliente desconectado:', socket.id);
    });
});

// Escuchar en todas las interfaces de red
server.listen(PORT, HOST, () => {
    console.log(`Servidor WebSocket ejecutándose en http://${HOST}:${PORT}`);
});

/*
const http = require('http');
const { Server } = require('socket.io');

const server = http.createServer((req, res) => {
    if (req.method === 'POST' && req.url === '/notificar') {
        let body = '';

        req.on('data', chunk => {
            body += chunk;
        });

        req.on('end', () => {
            let data = {};

            try {
                data = JSON.parse(body);
            } catch (e) {
                console.error('Error al parsear JSON:', e);
            }

            io.emit('actualizar_pantalla', data);

            // Solo imprimir si existe ticket
            if (data.ticket) {
                io.emit('imprimir_ticket', data);
            }
            // Enviar ticket a la aplicación local de impresión
            //io.emit('imprimir_ticket', data);

            res.writeHead(200, { 'Content-Type': 'application/json' });
            res.end(JSON.stringify({ ok: true }));
        });

        return;
    }

    res.writeHead(404);
    res.end();
});

const io = new Server(server, {
    cors: {
        origin: '*'
    }
});

io.on('connection', socket => {
    console.log('Cliente conectado:', socket.id);

    socket.on('disconnect', () => {
        console.log('Cliente desconectado:', socket.id);
    });
});

server.listen(3000, () => {
    console.log('Servidor WebSocket ejecutándose en puerto 3000');
});*/