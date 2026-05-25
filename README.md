# 🤖 IngeChat 360° — Interfaz Web del Asistente Virtual UNEFA

Interfaz web desarrollada en **Laravel 10** para el chatbot inteligente **IngeChat**, diseñado como asistente virtual institucional para la **Universidad Nacional Experimental Politécnica de la Fuerza Armada Nacional (UNEFA)**. La interfaz se comunica con un backend de IA externo (Python/FastAPI) mediante peticiones HTTP para responder preguntas de estudiantes y personal universitario.

---

## 📋 Tabla de contenidos

- [Descripción](#descripción)
- [Tecnologías utilizadas](#tecnologías-utilizadas)
- [Requisitos previos](#requisitos-previos)
- [Instalación](#instalación)
- [Configuración](#configuración)
- [Uso](#uso)
- [Estructura del proyecto](#estructura-del-proyecto)
- [Contribuidores](#contribuidores)
- [Licencia](#licencia)

---

## 📌 Descripción

**IngeChat 360°** es una interfaz web responsiva que permite a los usuarios interactuar con un chatbot de inteligencia artificial a través de un chat en tiempo real. La aplicación actúa como capa de presentación (frontend + backend Laravel) que recibe los mensajes del usuario y los envía a un servicio de IA corriendo localmente, mostrando las respuestas de forma dinámica en la pantalla.

Las características principales incluyen:

- Interfaz de chat limpia y moderna con identidad visual institucional de la UNEFA.
- Comunicación asíncrona con el backend de IA mediante Axios y Fetch.
- Diseño responsivo con Bootstrap 5.
- Tipografías corporativas (Montserrat y Open Sans).
- Fondo personalizado con imagen institucional.

---

## 🛠️ Tecnologías utilizadas

| Tecnología | Versión | Uso |
|---|---|---|
| PHP | ^8.1 | Lenguaje principal del backend |
| Laravel | ^10.10 | Framework principal |
| Laravel Sanctum | ^3.3 | Seguridad / CSRF |
| Bootstrap | ^5.3 | Estilos y componentes UI |
| Vite | ^5.0 | Empaquetador de assets |
| Axios | ^1.6.4 | Peticiones HTTP desde el frontend |
| Blade | — | Motor de plantillas de Laravel |
| Guzzle HTTP | ^7.2 | Peticiones HTTP desde el backend PHP |

---

## ✅ Requisitos previos

Antes de instalar el proyecto, asegúrate de tener:

- PHP >= 8.1
- Composer
- Node.js >= 18 y NPM
- Un servidor web local (Laragon, XAMPP, o el servidor de desarrollo de Artisan)
- El servicio de IA (backend Python/FastAPI) corriendo en `http://127.0.0.1:8000`

---

## 🚀 Instalación

Sigue estos pasos para levantar el proyecto en tu entorno local:

**1. Clona el repositorio**

```bash
git clone https://github.com/AlexanderAzocar/Interface-web-chatbot-IngeChat-360-.git
cd Interface-web-chatbot-IngeChat-360-
```

**2. Instala las dependencias de PHP**

```bash
composer install
```

**3. Instala las dependencias de Node.js**

```bash
npm install
```

**4. Copia el archivo de entorno**

```bash
cp .env.example .env
```

**5. Genera la clave de la aplicación**

```bash
php artisan key:generate
```

**6. Compila los assets**

```bash
npm run build
```

> Para desarrollo con hot reload usa: `npm run dev`

**7. Inicia el servidor de desarrollo**

```bash
php artisan serve
```

La aplicación estará disponible en: `http://localhost:8000`

---

## ⚙️ Configuración

Edita el archivo `.env` según tu entorno. Los campos más relevantes son:

```env
APP_NAME=IngeChat360
APP_ENV=local
APP_KEY=          # Se genera con php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

> **Importante:** El `ChatbotController` envía los mensajes al endpoint `http://127.0.0.1:8000/chat`. Asegúrate de que el backend de IA esté corriendo en esa dirección y puerto antes de usar el chat.

---

## 💬 Uso

1. Inicia el backend de IA (servicio Python/FastAPI) en el puerto `8000`.
2. Inicia Laravel en otro puerto (por ejemplo el `8080` con `php artisan serve --port=8080`).
3. Abre el navegador en la URL de Laravel.
4. Escribe tu pregunta en el campo de texto y presiona **Enviar**.
5. El asistente **IngeChat** responderá en tiempo real.

---

## 📁 Estructura del proyecto

Interface-web-chatbot-IngeChat-360-/
├── app/
│   └── Http/
│       └── Controllers/
│           └── ChatbotController.php   # Controlador principal del chatbot
├── resources/
│   └── views/
│       └── chatbot.blade.php           # Vista principal de la interfaz de chat
├── routes/
│   └── web.php                         # Definición de rutas web
├── public/                             # Assets públicos (imágenes, etc.)
├── bootstrap/                          # Archivos de arranque de Laravel
├── config/                             # Configuraciones del framework
├── database/                           # Migraciones y seeders
├── .env.example                        # Ejemplo de variables de entorno
├── composer.json                       # Dependencias PHP
├── package.json                        # Dependencias Node.js
└── vite.config.js                      # Configuración de Vite

---

## 👥 Contribuidores

| Usuario | Rol |
|---|---|
| [@AlexanderAzocar](https://github.com/AlexanderAzocar) | Desarrollador principal |
| [@NoSoyCrisman](https://github.com/NoSoyCrisman) | Diseño y estilos de la interfaz |

---

## 📄 Licencia

Este proyecto está bajo la licencia **MIT**. Consulta el archivo `composer.json` para más detalles.
