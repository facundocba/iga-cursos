# Guía Técnica - IGA Cursos Gastronómicos

Este documento contiene información técnica detallada sobre la implementación, arquitectura y configuración del sistema de venta de cursos gastronómicos de IGA.

## Tabla de Contenidos

1. [Arquitectura del Sistema](#arquitectura-del-sistema)
2. [Configuración de Docker](#configuración-de-docker)
3. [Backend (CodeIgniter 4)](#backend-codeigniter-4)
4. [Frontend (Vue.js)](#frontend-vuejs)
5. [Base de Datos](#base-de-datos)
6. [API REST](#api-rest)
7. [Autenticación](#autenticación)
8. [Flujo de Compra](#flujo-de-compra)
9. [Problemas Conocidos y Soluciones](#problemas-conocidos-y-soluciones)
10. [Mejoras Futuras](#mejoras-futuras)

## Arquitectura del Sistema

El sistema sigue una arquitectura cliente-servidor con los siguientes componentes:

- **Cliente (Frontend)**: Aplicación SPA desarrollada con Vue.js 3 que consume la API REST.
- **Servidor (Backend)**: API REST desarrollada con CodeIgniter 4 para la lógica.
- **Base de Datos**: MySQL para almacenamiento.
- **Servicios Adicionales**: PHPMyAdmin para la administración de la base de datos.

```
Cliente (Vue.js) <--> API REST (CodeIgniter) <--> Base de Datos (MySQL)
```

## Configuración de Docker

El sistema está completamente contenerizado utilizando Docker y Docker Compose:

- **MySQL**: Base de datos
- **CodeIgniter**: Backend/API
- **Vue.js**: Frontend
- **PHPMyAdmin**: Herramienta de administración de base de datos

El archivo `docker-compose.yml` define todos los servicios necesarios y sus configuraciones.

### Variables de entorno importantes:

```yaml
# MySQL
MYSQL_ROOT_PASSWORD: rootpassword
MYSQL_DATABASE: iga_cursos
MYSQL_USER: iga_user
MYSQL_PASSWORD: iga_password
MYSQL_CHARSET: utf8mb4
MYSQL_COLLATION: utf8mb4_unicode_ci
```

## Backend (CodeIgniter 4)

El backend está implementado con CodeIgniter 4, con una estructura MVC.

### Estructura principal:

- **Controllers**: Manejan las solicitudes HTTP y devuelven respuestas.
- **Models**: Encapsulan la lógica de negocio y el acceso a datos.
- **Filters**: Implementan middleware, como el filtro CORS.
- **Config**: Configuraciones del sistema (rutas, base de datos, etc.).

### Controllers principales:

- `CursosController`: Gestiona las operaciones CRUD para los cursos.
- `ComprasController`: Maneja la creación y consulta de compras.
- `AdminController`: Gestiona la autenticación de administradores.

### Models principales:

- `CursoModel`: Modelo para la tabla de cursos.
- `ClienteModel`: Modelo para la tabla de clientes.
- `CompraModel`: Modelo para la tabla de compras.
- `AdministradorModel`: Modelo para la tabla de administradores.

## Frontend (Vue.js)

El frontend está implementado con Vue.js 3, utilizando Vuex para la gestión del estado y Vue Router para la navegación.

### Estructura principal:

- **Components**: Componentes reutilizables.
- **Views**: Vistas principales de la aplicación.
- **Router**: Configuración de rutas.
- **Store**: Gestión del estado con Vuex.
- **Services**: Servicios para comunicación con la API.

### Vistas principales:

- `HomeView`: Página principal.
- `CursosView`: Catálogo de cursos.
- `DetallesCursoView`: Detalles de un curso específico.
- `CheckoutView`: Formulario de compra.
- `MisComprasView`: Historial de compras del cliente.
- `AdminLoginView`: Inicio de sesión para administradores.
- `AdminDashboardView`: Panel de administración.

## Base de Datos

La base de datos MySQL está estructurada con las siguientes tablas:

### Tabla: cursos

| Campo       | Tipo          | Descripción                   |
| ----------- | ------------- | ----------------------------- |
| id          | INT           | Identificador único (PK)      |
| nombre      | VARCHAR(255)  | Nombre del curso              |
| descripcion | TEXT          | Descripción breve             |
| precio      | DECIMAL(10,2) | Precio del curso              |
| detalle     | TEXT          | Descripción detallada         |
| imagen      | VARCHAR(255)  | Ruta a la imagen del curso    |
| created_at  | TIMESTAMP     | Fecha de creación             |
| updated_at  | TIMESTAMP     | Fecha de última actualización |

### Tabla: clientes

| Campo      | Tipo         | Descripción                   |
| ---------- | ------------ | ----------------------------- |
| id         | INT          | Identificador único (PK)      |
| nombre     | VARCHAR(255) | Nombre completo               |
| email      | VARCHAR(255) | Correo electrónico (único)    |
| telefono   | VARCHAR(20)  | Número de teléfono            |
| created_at | TIMESTAMP    | Fecha de creación             |
| updated_at | TIMESTAMP    | Fecha de última actualización |

### Tabla: compras

| Campo        | Tipo      | Descripción                              |
| ------------ | --------- | ---------------------------------------- |
| id           | INT       | Identificador único (PK)                 |
| cliente_id   | INT       | Referencia a cliente (FK)                |
| curso_id     | INT       | Referencia a curso (FK)                  |
| fecha_compra | TIMESTAMP | Fecha de la compra                       |
| estado       | ENUM      | Estado: pendiente, completada, cancelada |

### Tabla: administradores

| Campo      | Tipo         | Descripción                   |
| ---------- | ------------ | ----------------------------- |
| id         | INT          | Identificador único (PK)      |
| nombre     | VARCHAR(255) | Nombre completo               |
| email      | VARCHAR(255) | Correo electrónico (único)    |
| password   | VARCHAR(255) | Contraseña encriptada         |
| created_at | TIMESTAMP    | Fecha de creación             |
| updated_at | TIMESTAMP    | Fecha de última actualización |

## API REST

El backend expone los siguientes endpoints REST:

### Cursos

- `GET /api/cursos`: Obtiene todos los cursos.
- `GET /api/cursos/{id}`: Obtiene un curso específico.
- `POST /api/cursos`: Crea un nuevo curso (admin).
- `PUT /api/cursos/{id}`: Actualiza un curso (admin).
- `DELETE /api/cursos/{id}`: Elimina un curso (admin).

### Compras

- `GET /api/compras`: Obtiene todas las compras (admin).
- `GET /api/compras/estadisticas`: Obtiene estadísticas (admin).
- `GET /api/compras/mis-compras?email={email}`: Obtiene compras de un cliente.
- `POST /api/compras`: Registra una nueva compra.
- `PUT /api/compras/{id}/estado`: Actualiza el estado de una compra (admin).

### Administración

- `POST /api/admin/login`: Autentica a un administrador.
- `GET /api/admin/verificar-token`: Verifica la validez del token.

## Autenticación

El sistema implementa una autenticación básica para administradores:

1. El administrador proporciona credenciales (email/contraseña).
2. El backend verifica las credenciales y genera un token.
3. El token se almacena en localStorage y se envía en el encabezado `Authorization`.
4. Las rutas de administrador en el frontend están protegidas mediante un guardia de ruta.

Las credenciales predeterminadas del administrador son:

- Email: admin@iga.com
- Contraseña: password

## Flujo de Compra

1. El cliente navega por el catálogo de cursos y selecciona uno.
2. En la vista de detalles, el cliente hace clic en "Comprar Ahora".
3. El cliente completa un formulario con sus datos (nombre, email, teléfono).
4. Al confirmar, se envía una solicitud al endpoint `POST /api/compras`.
5. El backend registra al cliente si es nuevo o actualiza sus datos si ya existe.
6. Se crea un registro de compra vinculando cliente y curso.
7. Se muestra una confirmación al cliente.
8. El cliente puede ver su historial de compras en "Mis Cursos".
