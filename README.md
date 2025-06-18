# 💪 Fityron – Plataforma de Entrenamiento Personal con IA

Fityron es una aplicación web diseñada para transformar el entrenamiento personal mediante el uso de inteligencia artificial, seguimiento de progreso, visualización 3D y recomendaciones inteligentes. El objetivo es ofrecer a los usuarios una experiencia completa para entrenar desde cualquier lugar, de forma personalizada y efectiva.

---

## 🚀 Funcionalidades principales

- 🔐 Registro e inicio de sesión con autenticación segura.
- 🧠 Asistente virtual basado en IA (OpenAI API) para consultas y recomendaciones.
- 🧍 Visualización de modelo 3D interactivo con músculos y ejercicios.
- 📅 Generador de rutinas personalizadas basado en objetivos físicos.
- 📊 Seguimiento de progreso con gráficos y estadísticas.
- 📦 Base de datos escalable y modular (MySQL).
- 📱 Diseño responsivo y moderno.

---

## 🛠️ Tecnologías utilizadas

| Tecnología        | Uso                             |
|------------------|----------------------------------|
| PHP (Laravel)     | Backend y estructura MVC         |
| JavaScript/CSS    | Frontend y funcionalidades UI    |
| MySQL             | Base de datos relacional         |
| Three.js          | Modelo 3D interactivo            |
| API de OpenAI     | Asistente virtual de IA          |
| API Exercise DB   | Datos de ejercicios              |
| Git/GitHub        | Control de versiones             |
| Figma             | Diseño de interfaces             |
| Chart.js          | Gráficos y estadísticas          |
| XAMPP             | Entorno de desarrollo local      |

---


## ⚙️ Cómo ejecutar el proyecto en local

### Requisitos

- Visual Studio Code (o cualquier IDE)
- XAMPP (con Apache y MySQL)
- Composer
- Node.js y NPM

### Pasos

1. Clona el repositorio:
   ```bash
   git clone https://github.com/Jipanaqaba/FITYRON.git

2. Coloca el proyecto dentro de htdocs de XAMPP.

3. Inicia los servicios de Apache y MySQL desde XAMPP.
4. Crea la base de datos:
Nombre: fityron
5. Ejecuta migraciones desde VSCode:
```bash
php artisan migrate
--importar los ejercicios 
php artisan import:ejerciciosImportar
--en otra terminal ejecuta
npm install
npm run dev
--levanta el servidor
php artisan serve
```
6. Luego en el .env introduce todas tus credenciales de cada api para que funcione todo correctamente.



