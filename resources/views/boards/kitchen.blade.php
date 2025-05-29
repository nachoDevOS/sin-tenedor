<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablero Cocina - Restaurante</title>
    <style>
        /* Estilo IMPRIMIBLE y minimalista */
        body {
            font-family: 'Courier New', monospace;
            background: white;
            margin: 0;
            padding: 10px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px dashed #ccc;
        }
        .header h1 {
            font-size: 1.5rem;
            margin: 0;
        }
        .board {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .column {
            flex: 1;
            min-width: 200px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            background: #f9f9f9;
        }
        .column-title {
            font-weight: bold;
            text-align: center;
            padding: 5px;
            margin-bottom: 10px;
            background: #eee;
            border-radius: 3px;
        }
        .ticket {
            background: white;
            border-left: 4px solid #333;
            padding: 8px;
            margin-bottom: 10px;
            font-size: 0.9rem;
            page-break-inside: avoid; /* Evita que se corte al imprimir */
        }
        .ticket.new { border-left-color: #FF6B6B; }
        .ticket.cooking { border-left-color: #FFD166; }
        .ticket.ready { border-left-color: #06D6A0; }
        .ticket.delivered { border-left-color: #118AB2; }
        .ticket-table {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .ticket-time {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 5px;
        }
        .ticket-items {
            margin: 8px 0;
        }
        .ticket-item {
            margin-bottom: 3px;
        }
        .ticket-note {
            font-size: 0.8rem;
            background: #FFF3E0;
            padding: 3px;
            border-radius: 2px;
            margin-top: 5px;
        }
        @media print {
            body {
                padding: 0;
                font-size: 0.8rem;
            }
            .column {
                border: none;
                background: white;
                page-break-after: always; /* Fuerza nueva página por columna */
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>⚡ {{ setting('admin.title') }}- RESTAURANTE</h1>
        <p>Fecha: <span id="date"></span> | Hora: <span id="time"></span></p>
    </div>

    <div class="board">
        <!-- Columna: NUEVOS -->
        <div class="column">
            <div class="column-title">🆕 NUEVOS</div>
            <div class="ticket new">
                <div class="ticket-table">Mesa #5 (2 personas)</div>
                <div class="ticket-time">20:15</div>
                <div class="ticket-items">
                    <div class="ticket-item">1x Lasaña de carne</div>
                    <div class="ticket-item">1x Agua mineral</div>
                    <div class="ticket-item">1x Pan de ajo</div>
                </div>
                <div class="ticket-note">⚠️ Sin lácteos</div>
            </div>
            <div class="ticket new">
                <div class="ticket-table">Mesa #3 (4 personas)</div>
                <div class="ticket-time">20:20</div>
                <div class="ticket-items">
                    <div class="ticket-item">2x Pizza jamón/mushrooms</div>
                    <div class="ticket-item">1x Ensalada César</div>
                    <div class="ticket-item">2x Refrescos</div>
                </div>
            </div>
        </div>

        <!-- Columna: COCINA -->
        <div class="column">
            <div class="column-title">👨‍🍳 COCINA</div>
            <div class="ticket cooking">
                <div class="ticket-table">Mesa #2 (3 personas)</div>
                <div class="ticket-time">20:05</div>
                <div class="ticket-items">
                    <div class="ticket-item">3x Hamburguesas BBQ</div>
                    <div class="ticket-item">1x Papas fritas grande</div>
                    <div class="ticket-item">1x Cerveza artesanal</div>
                </div>
                <div class="ticket-note">🍔 1 sin cebolla</div>
            </div>
        </div>

        <!-- Columna: LISTO -->
        <div class="column">
            <div class="column-title">🔔 LISTO</div>
            <div class="ticket ready">
                <div class="ticket-table">Mesa #1 (2 personas)</div>
                <div class="ticket-time">19:55</div>
                <div class="ticket-items">
                    <div class="ticket-item">2x Pasta Alfredo</div>
                    <div class="ticket-item">1x Vino tinto</div>
                </div>
            </div>
        </div>

        <!-- Columna: ENTREGADO -->
        <div class="column">
            <div class="column-title">✅ ENTREGADO</div>
            <div class="ticket delivered">
                <div class="ticket-table">Mesa #4 (1 persona)</div>
                <div class="ticket-time">19:45</div>
                <div class="ticket-items">
                    <div class="ticket-item">1x Sopa del día</div>
                    <div class="ticket-item">1x Pan integral</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Actualiza fecha y hora automáticamente
        function updateDateTime() {
            const now = new Date();
            document.getElementById('date').textContent = now.toLocaleDateString();
            document.getElementById('time').textContent = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
        updateDateTime();
        setInterval(updateDateTime, 60000); // Actualiza cada minuto
    </script>
</body>
</html>