# IGA - Cursos Gastronómicos

Sistema web para la gestión y venta de cursos gastronómicos online para el Instituto de Gastronomía IGA

## Descripción del Proyecto

IGA Cursos Gastronómicos es una plataforma que permite a los clientes explorar y comprar cursos gastronómicos online. La plataforma incluye funcionalidades tanto para clientes como para administradores, permitiendo la gestión completa del ciclo de venta de cursos.

## Características Principales

- Catálogo de cursos gastronómicos
- Detalles específicos de cada curso
- Sistema de compra con registro de clientes
- Historial de compras para clientes
- Panel de administración con estadísticas y gestión
- Sistema completamente dockerizado

## Tecnologías Utilizadas

- **Frontend:** Vue.js 3 con Vuex y Vue Router
- **Backend:** CodeIgniter 4 (PHP)
- **Base de datos:** MySQL 8
- **Contenerización:** Docker y Docker Compose
- **Estilos:** Bootstrap 5
- **Control de versiones:** Git

## Estructura del Proyecto

```
iga-cursos-gastronomicos/
├── docker-compose.yml
├── database/
│   └── init.sql
├── backend/
│   ├── Dockerfile
│   ├── app/
│   │   ├── Config/
│   │   ├── Controllers/
│   │   ├── Models/
│   │   ├── Filters/
│   │   └── ...
│   └── ...
└── frontend/
    ├── Dockerfile
    ├── public/
    ├── src/
    │   ├── assets/
    │   ├── components/
    │   ├── views/
    │   ├── router/
    │   ├── store/
    │   ├── services/
    │   └── ...
    └── ...
```

## Requisitos

- Docker y Docker Compose
- Git
- Navegador web (Chrome, Firefox, Edge, etc.)

## Instalación y Configuración

1. **Clonar el repositorio:**

```bash
git clone https://github.com/tuusuario/iga-cursos-gastronomicos.git
cd iga-cursos-gastronomicos
```

2. **Instalar las dependencias:**

```bash
cd backend
composer install
```

```bash
cd ../frontend
npm install
```

3. **Iniciar los contenedores:**

```bash
docker-compose up -d
```

4. **Acceder a la aplicación:**
   - Frontend (Tienda): http://localhost:8080
   - Backend (API): http://localhost:8000
   - PHPMyAdmin: http://localhost:8081 (usuario: iga_user, contraseña: iga_password)

## Guías Adicionales

Para más información, consulta:

- [Guía Técnica](GUIA_TECNICA.md) - Detalles técnicos para desarrolladores
- [Manual de Usuario](MANUAL_USUARIO.md) - Instrucciones para usuarios finales

## Autor

- Facundo Córdoba - Desarrollador Full Stack
