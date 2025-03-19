# Sistema de Gestión de Cuentas Creactiva

Este proyecto es una aplicación de gestión de cuentas de usuario, roles y permisos, donde los super administradores, administradores, asistentes pueden gestionar el estado de las cuentas, asignar roles, y gestionar la autenticación de los usuarios.

## Descripción del Sistema

La aplicación permite la gestión de las cuentas de funcionarios y contratistas y sus roles mediante un conjunto de controladores que gestionan el inicio de sesión, la creación de cuentas, el cambio de estado de las cuentas y la asignación de roles y funciones.

### Funcionalidades Principales

- **Autenticación de Usuarios:** Los usuarios pueden autenticarse utilizando LDAP o la base de datos interna.
- **Gestión de Cuentas:** Los super administradores y administradores pueden crear y validar cuentas de usuario, verificar sus datos y cambiar su estado todo por medio de administracion manual o gestion de tickets de GLPI.
- **Asignación de Roles y Funciones:** Los administradores pueden asignar roles y funciones a los usuarios, así como bloquear o desbloquear cuentas de usuario, Realizar cargues masivos de regionales y administradores.
- **Control de Accesos:** Los administradores tienen la capacidad de gestionar los permisos de los usuarios asignándoles roles y funciones específicas.

## Clases y Controladores

### 1. **LoginController**
   Este controlador gestiona el inicio de sesión de los usuarios. Intenta autenticar al usuario mediante LDAP y, si no tiene éxito, verifica las credenciales en la base de datos.

   - **Método `login(Request $request)`**: 
     - Valida los datos de entrada (correo electrónico y contraseña).
     - Intenta autenticar al usuario con LDAP.
     - Si LDAP falla, verifica las credenciales en la base de datos.
     - Redirige al dashboard si la autenticación es exitosa, de lo contrario, muestra un mensaje de error.

### 2. **CreateAccountController**
   Este controlador maneja la creación de cuentas de usuario y su validación.

   - **Métodos**:
     - **`show()`**: Muestra la vista para crear cuentas.
     - **`store(Request $request)`**: Valida los datos de la cuenta, los guarda en la base de datos y envía un mensaje de éxito o error.

### 3. **ChangeStatusController**
   Controlador que permite cambiar el estado de las cuentas de usuario (activada, desactivada, etc.).

   - **Método `store(Request $request)`**: 
     - Valida los datos de entrada, identifica el tipo de cuenta (CreateAccount o ValidateAccount), y cambia el estado de la cuenta.

### 4. **RoleFunctionaryController**
   Este controlador permite gestionar los roles y las funciones de los usuarios, incluyendo la asignación de roles y el bloqueo/desbloqueo de cuentas de usuario.

   - **Métodos**:
     - **`show()`**: Muestra la vista con los roles, usuarios y regiones.
     - **`assignRoleFuncionary(Request $request)`**: Asigna roles y funciones a los usuarios seleccionados. También permite bloquear o desbloquear usuarios.
     - **`lockUsers($usersSelect)`**: Bloquea o desbloquea las cuentas de los usuarios seleccionados.

## Instalación

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/usuario/repo.git
   
## Instalar dependencias 

    composer install, npm y dependencias necesarias en el manual de despliegue.

## Generar la clave de la aplicacion

    php artisan key:generate

## Migrar la base de datos

    php artisan migrate

## CHANGELOG
## Versión 1.0.0
Fecha de lanzamiento:en curso

## Nuevas características:
Autenticación de Usuarios: Implementación de inicio de sesión utilizando LDAP y base de datos interna.
Gestión de Cuentas: Creación, validación y cambio de estado de cuentas de usuario.
Asignación de Roles y Funciones: Los administradores pueden asignar roles, bloquear/desbloquear usuarios y gestionar funciones de los mismos.
Mejoras:
Interfaz de usuario: Formularios de asignación de roles y gestión de cuentas mejorados para una experiencia más fluida.
Correcciones:
Solución de errores en la validación de datos durante el proceso de creación de cuentas.
Corrección de problemas en la asignación de roles a usuarios sin funciones asignadas.



