# Backend - API de Gestión de Empleados con Symfony

## 📌 Descripción del Proyecto
Este proyecto es una API RESTful desarrollada con Symfony para la gestión de empleados, incluyendo operaciones CRUD, autenticación con JWT, funcionalidad de eliminación lógica y notificaciones por correo electrónico.

## 🚀 Funcionalidades
- **Gestión de Empleados**: Crear, leer, actualizar y eliminar empleados.
- **Autenticación**: Inicio de sesión seguro con JWT.
- **Eliminación Lógica**: Los empleados son marcados como eliminados en lugar de ser eliminados permanentemente.
- **Notificaciones por Correo**: Se envía un correo de bienvenida cuando se registra un nuevo empleado.
- **Documentación de API**: Documentación generada con Swagger.
- **Caché**: Uso de Redis para almacenar consultas en caché.

## 🛠️ Tecnologías Utilizadas
- **Framework**: Symfony
- **Base de Datos**: MySQL/PostgreSQL con Doctrine ORM
- **Autenticación**: JWT (LexikJWTAuthenticationBundle)
- **Servicio de Correo**: Brevo/Mailgun
- **Caché**: Redis
- **Documentación API**: Swagger (NelmioApiDocBundle)

## 🏗️ Instalación y Configuración
### Requisitos Previos
- PHP 8.1+
- Composer
- MySQL/PostgreSQL

### Pasos de Instalación
1. **Clonar el repositorio:**
   ```sh
   git clone https://github.com/your-repo/unow_backend_empleados.git
   cd unow_backend_empleados
   ```
2. **Instalar dependencias:**
   ```sh
   composer install
   ```
3. **Configurar variables de entorno:**
   - Renombrar `.env.example` a `.env`
   - Configurar las credenciales de la base de datos y del servicio de correo
4. **Ejecutar migraciones de base de datos:**
   ```sh
   php bin/console doctrine:migrations:migrate
   ```
5. **Cargar datos iniciales (opcional):**
   ```sh
   php bin/console doctrine:fixtures:load
   ```
6. **Generar claves JWT:**
   ```sh
   php bin/console lexik:jwt:generate-keypair
   ```
7. **Iniciar el servidor de Symfony:**
   ```sh
   symfony server:start
   ```

## 📌 Endpoints de la API
### Autenticación
| Método | Endpoint       | Descripción        |
|--------|---------------|--------------------|
| POST   | `/api/login_check` | Autenticar usuario y obtener token JWT |
| POST   | `/api/register` | Registrar un nuevo usuario |

### Gestión de Empleados
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET    | `/api/employees/list` | Listar todos los empleados |
| GET    | `/api/employees/show/{id}` | Obtener detalles de un empleado |
| POST   | `/api/employees/create` | Crear un nuevo empleado |
| PUT    | `/api/employees/update/{id}` | Actualizar datos de un empleado |
| DELETE | `/api/employees/delete/{id}` | Eliminar un empleado de manera lógica |

## 🔑 Autenticación
Todas las rutas protegidas requieren un **Token Bearer**.
- Obtener un token en `/api/login_check`
- Enviar el token en la cabecera `Authorization`:
  ```sh
  Authorization: Bearer YOUR_TOKEN
  ```
  
## 🛠️ Documentación API con Swagger
La documentación Swagger está disponible en:
```sh
http://127.0.0.1:8000/api/doc
```

## 📌 Contacto
Prueba técnica para Unow