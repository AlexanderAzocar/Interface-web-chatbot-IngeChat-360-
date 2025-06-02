<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IngeChat UNEFA - Asistente Virtual</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Definición de Variables CSS para Colores Institucionales y Generales */
        :root {
            --unefa-blue: #003366; /* Azul oscuro característico de la UNEFA */
            --unefa-gold: #FFC300; /* Dorado para acentos, si aplica a su branding */
            --unefa-light-bg: #f5f8fa; /* Fondo general de la interfaz, suave */
            --message-bg-bot: #ffffff; /* Fondo blanco para mensajes del bot */
            --message-text-dark: #34495e; /* Color de texto oscuro para legibilidad */
            --border-light: #e0e6eb; /* Bordes sutiles */
            --shadow-subtle: rgba(0, 0, 0, 0.05); /* Sombra ligera */
            --shadow-medium: rgba(0, 0, 0, 0.15); /* Sombra un poco más marcada */
        }

        body {
            background: var(--unefa-light-bg) url('/unefa.jpg') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            margin: 0;
            font-family: 'Open Sans', sans-serif; /* Fuente principal legible */
            color: var(--message-text-dark);
            display: flex;
            justify-content: center;
            align-items: center; /* Centra el contenedor del chat en la pantalla */
        }

        .chat-container {
            max-width: 700px;
            width: 98%;
            margin: auto;
            background: var(--message-bg-bot);
            border-radius: 18px;
            box-shadow: 0 10px 40px var(--shadow-medium);
            display: flex;
            flex-direction: column;
            min-height: 800px;
            max-height: 900px;
            height: 800px;
            overflow: hidden;
            border: 1px solid var(--border-light);
        }

        .chat-header {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 24px 32px; /* Espaciado generoso */
            background: var(--unefa-blue); /* Color institucional fuerte para el encabezado */
            color: #fff;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: sticky; /* Permite que el encabezado se mantenga visible al hacer scroll */
            top: 0;
            z-index: 10; /* Asegura que esté por encima de otros elementos */
        }

        .chat-header img {
            width: 64px; /* Aumenta el tamaño del logo para mayor prominencia */
            height: 64px;
            border-radius: 50%;
            background: #fff; /* Fondo blanco para el logo */
            padding: 5px; /* Pequeño padding alrededor del logo */
            box-shadow: 0 2px 10px var(--shadow-subtle);
        }

        .chat-header h2 {
            margin: 0;
            font-size: 1.9rem; /* Tamaño de fuente más grande para el título */
            font-weight: 700;
            color: #fff;
            font-family: 'Montserrat', sans-serif; /* Fuente más robusta para el título */
            letter-spacing: 0.8px; /* Espaciado sutil entre letras */
        }

        .messages {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 32px 48px;
            background: var(--unefa-light-bg); /* Un fondo más claro para el área de mensajes */
            overflow-y: auto; /* Habilita el scroll vertical cuando los mensajes exceden el alto */
            scroll-behavior: smooth; /* Desplazamiento suave al agregar nuevos mensajes */
            min-height: 0;
            max-height: 650px; /* Limita el área de mensajes */
        }

        .message {
            margin-bottom: 16px; /* Espaciado entre burbujas de mensaje */
            max-width: 85%; /* Ancho máximo para los mensajes */
            padding: 14px 20px; /* Padding interno de las burbujas */
            border-radius: 20px; /* Bordes muy redondeados */
            font-size: 1.05rem;
            line-height: 1.5;
            word-break: break-word; /* Rompe palabras largas para evitar desbordamiento */
            box-shadow: 0 4px 12px var(--shadow-subtle); /* Sombra para dar profundidad */
            animation: fadeIn 0.3s ease-out; /* Animación de aparición suave */
            display: flex;
            align-items: flex-end;
            gap: 10px;
        }

        .message.user {
            background: var(--unefa-blue); /* Mensajes del usuario con el color institucional */
            color: #fff;
            align-self: flex-end; /* Alinea los mensajes del usuario a la derecha */
            border-bottom-right-radius: 8px; /* Esquina más "afilada" para un diseño moderno */
            flex-direction: row-reverse;
        }

        .message.bot {
            background: var(--message-bg-bot);
            color: var(--message-text-dark);
            align-self: flex-start; /* Alinea los mensajes del bot a la izquierda */
            border-bottom-left-radius: 8px; /* Esquina más "afilada" para un diseño moderno */
            border: 1px solid var(--border-light); /* Borde sutil para los mensajes del bot */
        }

        /* Indicador de "escribiendo" */
        .message.bot.typing-indicator {
            background: #e0e7ff; /* Fondo ligeramente diferente para el indicador */
            color: var(--message-text-dark);
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 4px;
            animation: pulseFade 1.5s infinite ease-in-out;
        }

        .typing-indicator span {
            animation: bounce 0.6s infinite alternate;
            opacity: 0.6;
        }
        .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
        .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }

        .input-area {
            flex-shrink: 0;
            padding: 20px 32px;
            background: rgba(255,255,255,0.2); /* Fondo transparente */
            border-top: 1px solid var(--border-light);
            backdrop-filter: blur(6px); /* Efecto de desenfoque para mayor legibilidad */
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .input-group {
            display: flex;
            gap: 12px; /* Espacio entre el input y el botón */
            align-items: center;
        }

        #user-input {
            flex: 1; /* Ocupa el espacio disponible */
            border-radius: 24px; /* Muy redondeado */
            border: 1.5px solid var(--border-light);
            font-size: 1.05rem;
            padding: 12px 20px;
            background: var(--unefa-light-bg);
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.03); /* Sombra interna sutil */
        }

        .input-group .form-control {
            background: rgba(255,255,255,0.5);
            border: 1.5px solid var(--border-light);
            color: var(--message-text-dark);
            box-shadow: none;
        }
        .input-group .form-control:focus {
            background: rgba(255,255,255,0.7);
            border-color: var(--unefa-blue);
            outline: none;
        }

        .btn-primary {
            border-radius: 24px;
            background: var(--unefa-blue);
            border: none;
            font-weight: 600;
            font-size: 1.05rem;
            padding: 12px 28px;
            box-shadow: 0 4px 12px rgba(0, 51, 102, 0.2); /* Sombra para el botón */
            transition: background 0.3s ease-in-out, transform 0.2s ease-in-out;
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--unefa-gold); /* Cambio de color al pasar el mouse */
            transform: translateY(-2px); /* Pequeño efecto de "levantamiento" */
            box-shadow: 0 6px 16px rgba(0, 51, 102, 0.3);
        }
        .btn-primary:active {
            transform: translateY(0); /* Vuelve a su posición original al hacer click */
            box-shadow: 0 2px 8px rgba(0, 51, 102, 0.2);
        }

        /* Keyframe Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-3px); }
        }
        @keyframes pulseFade {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        /* Responsividad */
        @media (max-width: 768px) {
            .chat-container {
                max-width: 100vw;
                width: 100%;
                margin: 0;
                border-radius: 0; /* Sin bordes redondeados en vista móvil para ocupar toda la pantalla */
                min-height: 100vh; /* Ocupa toda la altura disponible */
            }
            .chat-header, .messages, .input-area {
                padding-left: 20px;
                padding-right: 20px;
            }
            .chat-header h2 {
                font-size: 1.7rem;
            }
            .message {
                font-size: 0.95rem;
                padding: 10px 15px;
            }
            #user-input, .btn-primary {
                font-size: 0.95rem;
                padding: 10px 18px;
            }
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }
        .message span { display: inline-block; }
    </style>
</head>
<body>
<div class="chat-container">
    <div class="chat-header">
        <img src="/logo_unefa.png" alt="Logo UNEFA">
        <h2>IngeChat 360 UNEFA</h2>
    </div>
    <div class="messages" id="messages">
        <div class="message bot">¡Hola! Soy Ingechat 360, tu asistente virtual de la UNEFA. Estoy aquí para ayudarte con tus consultas académicas y administrativas. ¿En qué puedo asistirte hoy?</div>
    </div>
    <form id="chat-form" class="input-area">
        <div class="input-group">
            <input type="text" id="user-input" class="form-control" placeholder="Escribe tu mensaje..." required autocomplete="off" aria-label="Escribe tu mensaje para el asistente virtual">
            <button class="btn btn-primary" type="submit" aria-label="Enviar mensaje">Enviar</button>
        </div>
    </form>
</div>
<script>
    const messagesDiv = document.getElementById('messages');
    const chatForm = document.getElementById('chat-form');
    const userInput = document.getElementById('user-input');

    /**
     * Agrega un nuevo mensaje al área de chat.
     * @param {string} text - El contenido del mensaje.
     * @param {string} sender - El remitente del mensaje ('user' o 'bot').
     * @param {boolean} isTyping - Si es un mensaje de "escribiendo".
     */
    function appendMessage(text, sender, isTyping = false) {
        const msg = document.createElement('div');
        msg.className = `message ${sender}` + (isTyping ? ' typing-indicator' : '');
        // Agrega imagen de usuario o bot
        const avatar = document.createElement('img');
        avatar.className = 'avatar';
        if (sender === 'user') {
            avatar.src = '/user_avatar.jpg';
            avatar.alt = 'Usuario';
        } else {
            avatar.src = isTyping ? '/send_icon.png' : '/bot_avatar.png';
            avatar.alt = isTyping ? 'Enviando...' : 'Bot';
        }
        msg.appendChild(avatar);
        // Mensaje o puntos animados
        if (isTyping) {
            const typingSpan = document.createElement('span');
            typingSpan.innerHTML = '<span>.</span><span>.</span><span>.</span>';
            msg.appendChild(typingSpan);
        } else {
            const textNode = document.createElement('span');
            textNode.textContent = text;
            msg.appendChild(textNode);
        }
        messagesDiv.appendChild(msg);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault(); // Previene el comportamiento por defecto del formulario

        const text = userInput.value.trim();
        if (!text) return; // No hace nada si el input está vacío

        appendMessage(text, 'user'); // Muestra el mensaje del usuario
        userInput.value = ''; // Limpia el campo de entrada

        // Indicador de escribiendo con imagen
        const typingMsg = document.createElement('div');
        typingMsg.className = 'message bot typing-indicator';
        const botAvatar = document.createElement('img');
        botAvatar.className = 'avatar';
        botAvatar.src = '/send_icon.png';
        botAvatar.alt = 'Enviando...';
        typingMsg.appendChild(botAvatar);
        const typingSpan = document.createElement('span');
        typingSpan.innerHTML = '<span>.</span><span>.</span><span>.</span>';
        typingMsg.appendChild(typingSpan);
        messagesDiv.appendChild(typingMsg);
        messagesDiv.scrollTop = messagesDiv.scrollHeight; // Desplaza para ver el indicador

        try {
            const response = await fetch('/preguntar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: text })
            });

            // Lanza un error si la respuesta no es OK
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            // Elimina el indicador de "escribiendo" antes de mostrar la respuesta real
            if (messagesDiv.contains(typingMsg)) {
                messagesDiv.removeChild(typingMsg);
            }

            // Muestra la respuesta del bot o un mensaje de fallback
            appendMessage(data.response || 'Disculpa, no pude obtener una respuesta en este momento. Por favor, intenta reformular tu pregunta o comunícate con la administración.', 'bot');
        } catch (err) {
            console.error('Error al conectar con el bot o procesar la respuesta:', err);
            // Elimina el indicador de "escribiendo" en caso de error
            if (messagesDiv.contains(typingMsg)) {
                messagesDiv.removeChild(typingMsg);
            }
            appendMessage('Lo sentimos, ha ocurrido un error al intentar conectar con el asistente. Por favor, revisa tu conexión a internet o inténtalo de nuevo más tarde.', 'bot');
        } finally {
            // Asegúrate de que el input esté habilitado de nuevo si se deshabilitó durante el envío
            userInput.disabled = false;
        }
    });

    // Opcional: Deshabilitar el input mientras se espera la respuesta para evitar envíos dobles
    chatForm.addEventListener('submit', () => {
        userInput.disabled = true;
    });

    // Estilos para los avatares
    const style = document.createElement('style');
    style.innerHTML = `
        .message { display: flex; align-items: flex-end; gap: 10px; }
        .message.user { flex-direction: row-reverse; }
        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }
        .message span { display: inline-block; }
    `;
    document.head.appendChild(style);
</script>
</body>
</html>