<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IngeChat UNEFA - Asistente Virtual</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
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

        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            background: var(--unefa-light-bg) url('/unefa.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Open Sans', sans-serif;
            color: var(--message-text-dark);
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .chat-container {
            max-width: 700px;
            width: 98%;
            margin: auto;
            background: var(--message-bg-bot);
            border-radius: 18px;
            box-shadow: 0 15px 50px var(--shadow-medium);
            display: flex;
            flex-direction: column;
            min-height: 800px;
            max-height: 900px;
            height: 800px;
            overflow: hidden;
            border: 1px solid var(--border-light);

            @media (min-width: 769px) {
                max-width: 900px;
                width: 90%;
                margin: 0 auto;
                height: 95vh;
                border-radius: 18px;
            }
        }

        .chat-header {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 24px 32px;
            background: var(--unefa-blue);
            color: #fff;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: sticky;
            top: 0;
            z-index: 10;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            justify-content: center;
        }

        .chat-header img {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            background: #fff;
            padding: 4px;
            box-shadow: 0 2px 10px var(--shadow-subtle);
            object-fit: contain;
        }

        .chat-header h2 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            letter-spacing: 0.8px;
        }

        .messages {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 32px 48px;
            background: var(--unefa-light-bg);
            overflow-y: auto;
            scroll-behavior: smooth;
            min-height: 0;

            @media (min-width: 769px) {
                padding: 32px 60px;
            }
        }

        .message-wrapper {
            display: flex;
            align-items: flex-end;
            margin-bottom: 16px;
            max-width: 85%;
            gap: 10px;
        }

        .message {
            padding: 14px 20px;
            border-radius: 20px;
            font-size: 1.05rem;
            line-height: 1.5;
            word-break: break-word;
            box-shadow: 0 4px 12px var(--shadow-subtle);
            animation: fadeIn 0.3s ease-out;
            position: relative;
        }

        /* --- CAMBIOS AQUÍ para posicionar el avatar del usuario a la DERECHA --- */
        .message-wrapper.user {
            align-self: flex-end; /* Alinea todo el wrapper a la derecha */
            flex-direction: row-reverse; /* Esto pone el avatar a la derecha */
        }

        .message-wrapper.user .message {
            background: var(--unefa-blue);
            color: #fff;
            border-radius: 20px 20px 4px 20px; /* Esquina inferior derecha menos redondeada (botón) */
        }

        .message-wrapper.bot {
            align-self: flex-start; /* Alinea todo el wrapper a la izquierda */
            flex-direction: row; /* Asegura que el avatar del bot esté a la izquierda */
        }

        .message-wrapper.bot .message {
            background: var(--message-bg-bot);
            color: var(--message-text-dark);
            border: 1px solid var(--border-light);
            border-radius: 20px 20px 20px 4px; /* Esquina inferior izquierda menos redondeada (bot) */
        }

        .message-wrapper .avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            flex-shrink: 0;
            border: 1px solid var(--border-light);
            align-self: flex-end;
        }

        .message-wrapper.bot .message.typing-indicator {
            background: #e9eff5;
            color: var(--message-text-dark);
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 4px;
            animation: pulseFade 1.5s infinite ease-in-out;
            padding: 12px 18px;
            border-radius: 20px 20px 20px 4px;
        }

        .typing-indicator span {
            animation: bounce 0.6s infinite alternate;
            opacity: 0.6;
            font-weight: 700;
            color: var(--unefa-blue);
        }
        .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
        .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }


        .input-area {
            flex-shrink: 0;
            padding: 20px 32px;
            background: rgba(255,255,255,0.2);
            border-top: 1px solid var(--border-light);
            backdrop-filter: blur(6px);
            box-shadow: 0 -2px 8px rgba(0,0,0,0.04);

            @media (min-width: 769px) {
                padding: 20px 60px;
            }
        }

        .input-group {
            display: flex;
            align-items: center;
            border-radius: 26px;
            overflow: hidden;
            border: 1.5px solid var(--border-light);
            background: var(--unefa-light-bg);
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.03);
        }

        #user-input {
            flex: 1;
            border: none;
            border-radius: 0;
            padding: 13px 20px;
            background: transparent;
            box-shadow: none;
            transition: none;
            color: var(--message-text-dark);
        }

        #user-input:focus {
            border-color: transparent;
            box-shadow: none;
            background: transparent;
            outline: none;
        }

        .btn-primary {
            flex-shrink: 0;
            border-radius: 0 26px 26px 0;
            background: var(--unefa-blue);
            border: none;
            font-weight: 600;
            font-size: 1.05rem;
            padding: 13px 25px;
            box-shadow: none;
            transition: background 0.3s ease-in-out, transform 0.2s ease-in-out;
            color: #fff;
            border-left: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-primary:hover {
            background: var(--unefa-gold);
            transform: translateY(0);
            box-shadow: none;
        }
        .btn-primary:active {
            background: var(--unefa-blue);
            transform: translateY(0);
            box-shadow: none;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }
        @keyframes pulseFade {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        @media (max-width: 768px) {
            .chat-container {
                max-width: 100vw;
                width: 100%;
                margin: 0;
                border-radius: 0;
                min-height: 100vh;
                height: 100vh;
            }
            .chat-header, .messages, .input-area {
                padding-left: 20px;
                padding-right: 20px;
            }
            .chat-header h2 {
                font-size: 1.6rem;
            }
            .chat-header img {
                width: 50px;
                height: 50px;
            }
            .messages {
                padding: 20px;
                max-height: calc(100vh - 180px);
            }
            .message-wrapper {
                max-width: 90%;
                gap: 8px;
            }
            .message {
                font-size: 0.92rem;
                padding: 10px 15px;
                border-radius: 12px;
            }
            .message-wrapper.user .message {
                border-radius: 12px 12px 4px 12px;
            }
            .message-wrapper.bot .message {
                border-radius: 12px 12px 12px 4px;
            }
            .message-wrapper .avatar {
                width: 30px;
                height: 30px;
            }
            #user-input, .btn-primary {
                font-size: 0.95rem;
                padding: 10px 18px;
            }
            .input-group {
                border-radius: 0;
                flex-direction: row;
                flex-wrap: nowrap;
            }
            .btn-primary {
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
<div class="chat-container">
    <div class="chat-header">
        <img src="/logo_unefa.png" alt="Logo UNEFA">
        <h2>IngeChat 360 UNEFA</h2>
    </div>
    <div class="messages" id="messages">
        <div class="message-wrapper bot">
            <img src="/bot_avatar.png" alt="Bot" class="avatar">
            <div class="message">
                <span>¡Hola! Soy Ingechat 360, tu asistente virtual de la UNEFA. Estoy aquí para ayudarte con tus consultas académicas y administrativas. ¿En qué puedo asistirte hoy?</span>
            </div>
        </div>
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
     * @param {string} content - El contenido del mensaje (texto).
     * @param {string} sender - El remitente del mensaje ('user' o 'bot').
     * @param {boolean} isTyping - Si es un mensaje de "escribiendo".
     */
    function appendMessage(content, sender, isTyping = false) {
        const messageWrapper = document.createElement('div');
        messageWrapper.className = `message-wrapper ${sender}`;

        const avatar = document.createElement('img');
        avatar.className = 'avatar';
        if (sender === 'user') {
            avatar.src = '/user_avatar.jpg';
            avatar.alt = 'Usuario';
        } else {
            avatar.src = '/bot_avatar.png';
            avatar.alt = 'Bot';
        }

        const msgContentDiv = document.createElement('div');
        msgContentDiv.className = 'message';

        if (isTyping) {
            msgContentDiv.classList.add('typing-indicator');
            const typingSpan = document.createElement('span');
            typingSpan.innerHTML = '<span>.</span><span>.</span><span>.</span>';
            msgContentDiv.appendChild(typingSpan);
        } else {
            const textNode = document.createElement('span');
            textNode.textContent = content;
            msgContentDiv.appendChild(textNode);
        }

        // --- CAMBIOS EN LA LÓGICA DE APPENDMESSAGE ---
        if (sender === 'user') {
            messageWrapper.appendChild(avatar);
            messageWrapper.appendChild(msgContentDiv);
        } else {
            messageWrapper.appendChild(avatar);
            messageWrapper.appendChild(msgContentDiv);
        }
        // --- FIN CAMBIOS ---

        messagesDiv.appendChild(messageWrapper);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();

        const text = userInput.value.trim();
        if (!text) return;

        appendMessage(text, 'user');
        userInput.value = '';
        userInput.disabled = true;

        appendMessage('', 'bot', true);
        const typingMsgElement = messagesDiv.lastChild.querySelector('.typing-indicator');

        try {
            const response = await fetch('/preguntar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message: text })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (typingMsgElement && messagesDiv.contains(typingMsgElement.parentNode)) {
                messagesDiv.removeChild(typingMsgElement.parentNode);
            }

            appendMessage(data.response || 'Disculpa, no pude obtener una respuesta en este momento. Por favor, intenta reformular tu pregunta o comunícate con la administración.', 'bot');

        } catch (err) {
            console.error('Error al conectar con el bot o procesar la respuesta:', err);
            if (typingMsgElement && messagesDiv.contains(typingMsgElement.parentNode)) {
                messagesDiv.removeChild(typingMsgElement.parentNode);
            }
            appendMessage('Lo sentimos, ha ocurrido un error al intentar conectar con el asistente. Por favor, revisa tu conexión a internet o inténtalo de nuevo más tarde.', 'bot');
        } finally {
            userInput.disabled = false;
            userInput.focus();
        }
    });
</script>
</body>
</html>