<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ setting('admin.title') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #e63946;
            --secondary: #f1faee;
            --accent: #a8dadc;
            --dark: #1d3557;
            --light: #ffffff;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: var(--dark);
        }
        
        .restaurant-header {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: var(--light);
            width: 100%;
            text-align: center;
            padding: 30px 0;
            margin-bottom: 30px;
            position: relative;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .restaurant-header h1 {
            margin: 0;
            font-size: 2.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        
        .restaurant-header p {
            margin: 10px 0 0;
            font-size: 1.2rem;
            font-weight: 300;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .display-system {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-bottom: 40px;
        }
        
        .current-display {
            flex: 1;
            min-width: 300px;
            background-color: var(--light);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        
        .current-display:hover {
            transform: translateY(-5px);
        }
        
        .display-header {
            background-color: var(--primary);
            color: var(--light);
            padding: 15px;
            text-align: center;
            font-size: 1.3rem;
            font-weight: 600;
        }
        
        .current-ticket {
            padding: 40px 20px;
            text-align: center;
            background-color: var(--secondary);
            position: relative;
            overflow: hidden;
        }
        
        .current-ticket::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 8px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
        }
        
        .ticket-number {
            font-size: 5rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0;
            line-height: 1;
        }
        
        .ticket-message {
            font-size: 1.5rem;
            color: var(--dark);
            margin-top: 10px;
        }
        
        .queue-display {
            flex: 1;
            min-width: 300px;
            background-color: var(--light);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .queue-header {
            background-color: var(--dark);
            color: var(--light);
            padding: 15px;
            text-align: center;
            font-size: 1.3rem;
            font-weight: 600;
        }
        
        .queue-content {
            padding: 25px;
            background-color: var(--secondary);
        }
        
        .queue-title {
            text-align: center;
            color: var(--dark);
            margin-bottom: 20px;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .queue-title svg {
            margin-right: 10px;
        }
        
        .queue-items {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 15px;
        }
        
        .ticket-item {
            background-color: var(--light);
            color: var(--dark);
            padding: 15px 10px;
            border-radius: 8px;
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
            border-left: 5px solid var(--accent);
            position: relative;
            overflow: hidden;
        }
        
        .ticket-item.next {
            background-color: var(--primary);
            color: var(--light);
            transform: scale(1.05);
            border-left-color: var(--dark);
        }
        
        .ticket-item.next::after {
            content: "PRÓXIMO";
            position: absolute;
            top: 5px;
            right: 5px;
            font-size: 0.6rem;
            font-weight: 600;
            background-color: var(--dark);
            color: var(--light);
            padding: 2px 5px;
            border-radius: 3px;
        }
        
        .controls-panel {
            background-color: var(--light);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 40px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .controls-title {
            text-align: center;
            margin-bottom: 25px;
            color: var(--dark);
            font-size: 1.5rem;
            position: relative;
        }
        
        .controls-title::after {
            content: "";
            display: block;
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            margin: 10px auto 0;
            border-radius: 2px;
        }
        
        .controls-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        
        .control-group {
            display: flex;
            flex-direction: column;
        }
        
        .control-label {
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark);
        }
        
        .counter-select {
            padding: 12px 15px;
            border-radius: 8px;
            border: 2px solid #e9ecef;
            font-size: 1rem;
            background-color: var(--light);
            transition: all 0.3s;
        }
        
        .counter-select:focus {
            border-color: var(--accent);
            outline: none;
            box-shadow: 0 0 0 3px rgba(168, 218, 220, 0.3);
        }
        
        .buttons-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn svg {
            margin-right: 8px;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: var(--light);
        }
        
        .btn-primary:hover {
            background-color: #d62839;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(230, 57, 70, 0.3);
        }
        
        .btn-secondary {
            background-color: var(--accent);
            color: var(--dark);
        }
        
        .btn-secondary:hover {
            background-color: #92c7c9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(168, 218, 220, 0.3);
        }
        
        .btn-dark {
            background-color: var(--dark);
            color: var(--light);
        }
        
        .btn-dark:hover {
            background-color: #142a4e;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(29, 53, 87, 0.3);
        }
        
        .flashing {
            animation: flash 1s infinite;
        }
        
        @keyframes flash {
            0% { opacity: 1; }
            50% { opacity: 0.7; transform: scale(1.02); }
            100% { opacity: 1; }
        }
        
        .restaurant-info {
            background-color: var(--dark);
            color: var(--light);
            padding: 20px;
            text-align: center;
            border-radius: 0 0 15px 15px;
        }
        
        .restaurant-info p {
            margin: 5px 0;
            font-size: 0.9rem;
        }
        
        .restaurant-logo {
            max-width: 150px;
            margin-bottom: 15px;
        }
        
        @media (max-width: 768px) {
            .ticket-number {
                font-size: 3.5rem;
            }
            
            .ticket-message {
                font-size: 1.2rem;
            }
            
            .queue-items {
                grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="restaurant-header">
        <h1>{{ setting('admin.title') }}</h1>
        <p>Sistema de gestión de turnos</p>
    </div>
    
    <div class="container">
        <div class="display-system">
            <div class="current-display">
                <div class="display-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    TURNO ACTUAL
                </div>
                <div class="current-ticket">
                    <h2 class="ticket-number" id="currentTicket">A01</h2>
                    <p class="ticket-message">Por favor acérquese</p>
                </div>
                <div class="restaurant-info">
                    <img src="https://via.placeholder.com/150x50/1d3557/ffffff?text=Delicias" alt="Logo Restaurante Delicias" class="restaurant-logo">
                    <p>Av. Principal 1234, Ciudad</p>
                    <p>Horario: 12:00 - 23:00 hrs</p>
                </div>
            </div>
            
            <div class="queue-display">
                <div class="queue-header">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle;">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    PRÓXIMOS TURNOS
                </div>
                <div class="queue-content">
                    <div class="queue-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        Hoy, 12 de junio
                    </div>
                    <div class="queue-items" id="ticketQueue">
                        <!-- Los tickets en cola aparecerán aquí -->
                    </div>
                </div>
            </div>
        </div>
        
        <div class="controls-panel">
            <h2 class="controls-title">Panel de Control</h2>
            
            <div class="controls-grid">
                <div class="control-group">
                    <label for="counterSelect" class="control-label">Mesero asignado</label>
                    <select class="counter-select" id="counterSelect">
                        <option value="1">Mesero: Juan Pérez</option>
                        <option value="2">Mesero: María González</option>
                        <option value="3">Mesero: Carlos López</option>
                    </select>
                </div>
                
                <div class="control-group">
                    <label for="ticketType" class="control-label">Tipo de cliente</label>
                    <select class="counter-select" id="ticketType">
                        <option value="A">Individual (A)</option>
                        <option value="B">Pareja (B)</option>
                        <option value="C">Familia (C)</option>
                        <option value="D">Grupo (D)</option>
                    </select>
                </div>
            </div>
            
            <div class="buttons-grid">
                <button class="btn btn-primary" id="callBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                    Llamar siguiente
                </button>
                
                <button class="btn btn-secondary" id="addTicketBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="12" y1="18" x2="12" y2="12"></line>
                        <line x1="9" y1="15" x2="15" y2="15"></line>
                    </svg>
                    Nuevo turno
                </button>
                
                <button class="btn btn-dark" id="resetBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="1 4 1 10 7 10"></polyline>
                        <polyline points="23 20 23 14 17 14"></polyline>
                        <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path>
                    </svg>
                    Reiniciar
                </button>
            </div>
        </div>
    </div>
    
    <script>
        // Variables de estado
        let currentType = 'A';
        let currentNumber = 1;
        let queue = [];
        let waiters = [
            { id: 1, name: 'Juan Pérez', currentTicket: null },
            { id: 2, name: 'María González', currentTicket: null },
            { id: 3, name: 'Carlos López', currentTicket: null }
        ];
        
        // Elementos del DOM
        const currentTicketElement = document.getElementById('currentTicket');
        const ticketQueueElement = document.getElementById('ticketQueue');
        const counterSelectElement = document.getElementById('counterSelect');
        const ticketTypeElement = document.getElementById('ticketType');
        const callBtnElement = document.getElementById('callBtn');
        const addTicketBtnElement = document.getElementById('addTicketBtn');
        const resetBtnElement = document.getElementById('resetBtn');
        
        // Funciones
        function generateTicket() {
            const ticketNumber = currentNumber.toString().padStart(2, '0');
            const ticket = `${currentType}${ticketNumber}`;
            
            currentNumber++;
            if (currentNumber > 99) {
                currentNumber = 1;
            }
            
            return ticket;
        }
        
        function addToQueue(ticket) {
            queue.push(ticket);
            updateQueueDisplay();
        }
        
        function callNextTicket() {
            if (queue.length === 0) {
                alert('No hay turnos en espera');
                return;
            }
            
            const selectedWaiterId = parseInt(counterSelectElement.value);
            const selectedWaiter = waiters.find(w => w.id === selectedWaiterId);
            
            // Quitar el primer ticket de la cola
            const nextTicket = queue.shift();
            selectedWaiter.currentTicket = nextTicket;
            
            // Actualizar la pantalla
            currentTicketElement.textContent = nextTicket;
            currentTicketElement.classList.add('flashing');
            
            // Quitar el efecto de flashing después de 2 segundos
            setTimeout(() => {
                currentTicketElement.classList.remove('flashing');
            }, 2000);
            
            updateQueueDisplay();
            
            // Reproducir sonido de llamada (simulado con alert)
            alert(`Turno ${nextTicket} - Por favor acérquese`);
        }
        
        function updateQueueDisplay() {
            ticketQueueElement.innerHTML = '';
            
            // Mostrar los próximos 6 tickets (o menos si no hay tantos)
            const ticketsToShow = queue.slice(0, 6);
            
            ticketsToShow.forEach((ticket, index) => {
                const ticketElement = document.createElement('div');
                ticketElement.className = 'ticket-item' + (index === 0 ? ' next' : '');
                ticketElement.textContent = ticket;
                
                // Añadir tooltip con tipo de cliente
                let clientType = '';
                switch(ticket.charAt(0)) {
                    case 'A': clientType = 'Individual'; break;
                    case 'B': clientType = 'Pareja'; break;
                    case 'C': clientType = 'Familia'; break;
                    case 'D': clientType = 'Grupo'; break;
                }
                ticketElement.title = `${clientType} - ${ticket}`;
                
                ticketQueueElement.appendChild(ticketElement);
            });
        }
        
        function resetSystem() {
            if (confirm('¿Estás seguro de que quieres reiniciar el sistema de turnos?')) {
                currentType = 'A';
                currentNumber = 1;
                queue = [];
                waiters.forEach(w => w.currentTicket = null);
                currentTicketElement.textContent = 'A01';
                updateQueueDisplay();
            }
        }
        
        // Event listeners
        callBtnElement.addEventListener('click', callNextTicket);
        
        addTicketBtnElement.addEventListener('click', () => {
            currentType = ticketTypeElement.value;
            const newTicket = generateTicket();
            addToQueue(newTicket);
        });
        
        resetBtnElement.addEventListener('click', resetSystem);
        
        // Inicializar con algunos tickets
        ticketTypeElement.value = 'A';
        addToQueue(generateTicket());
        ticketTypeElement.value = 'B';
        addToQueue(generateTicket());
        ticketTypeElement.value = 'C';
        addToQueue(generateTicket());
        ticketTypeElement.value = 'A';
        addToQueue(generateTicket());
    </script>
</body>
</html>