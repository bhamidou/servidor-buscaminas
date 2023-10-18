# 🚀 Proyecto Buscaminas en PHP

¡Bienvenido al repositorio del proyecto Buscaminas en PHP! Este proyecto ofrece un emocionante juego de Buscaminas en línea, donde los jugadores pueden competir y guardar sus registros.

## 📋 Tabla de Contenidos

- [Demo](#demo)
- [Características](#características)
- [Requisitos](#requisitos)
- [Instalación](#instalación)
- [Uso](#uso)
- [Contribución](#contribución)
- [Licencia](#licencia)

## 🎮 Demo

¿Quieres ver cómo funciona el Buscaminas en acción? ¡Accede a la [demo en vivo](https://servidor.badrweb.es) ahora mismo!

![GIF](https://user-images.githubusercontent.com/74038190/216644507-4f06ea29-bf55-4356-aac0-d42751461a9d.gif)

## 🌟 Características

- Juego clásico de Buscaminas en una línea.
- CRUD completo para gestionar jugadores.
- Puntuaciones de jugadores guardadas para competir en el tablero de líderes.
- Interfaz de usuario amigable y atractiva.
- Seguridad de datos para proteger la información del jugador.

## 📋 Requisitos

Antes de comenzar, asegúrate de tener los siguientes requisitos:

- Servidor web (por ejemplo, Apache) con soporte para PHP.
- PHP 7.0 o superior.
- MySQL o cualquier otro sistema de gestión de bases de datos compatible.

## ⬇️ Instalación

1. Clona este repositorio en tu servidor web:

   ```bash
   git clone https://github.com/bhamidou/buscaminas-php.git
   ```

2. Crea una base de datos MySQL y configura las credenciales en `Constantes.php`.

3. Ejecuta el script SQL proporcionado en `database.sql` para crear las tablas necesarias en tu base de datos.

## 🚀 Uso | Endpoints del Programa

A continuación se enumeran los endpoints disponibles:

### Rutas de Administrador (`/admin`)

#### GET `/admin/users`
- Descripción: Obtiene la lista de usuarios.
- Ejemplo de uso: `/admin/users`

#### GET `/admin/user/{id}`
- Descripción: Obtiene los detalles de un usuario específico por su ID.
- Ejemplo de uso: `/admin/user/123`

#### POST `/admin/user`
- Descripción: Crea un nuevo usuario.
- Ejemplo de uso: `/admin/user`
- Parámetros requeridos: `user` (Datos del usuario).

#### POST `/admin/user`
- Descripción: Actualiza el nombre y el rol de un usuario.
- Ejemplo de uso: `/admin/user`
- Parámetros requeridos: `update` (Datos de actualización).

#### DELETE `/admin/user/{id}`
- Descripción: Elimina un usuario por su ID.
- Ejemplo de uso: `/admin/user/123`

### Rutas Generales
A estas rutas solo podrán acceder usuarios registrados, pero con cualquier tipo de rol.

#### GET `/jugar`
- Descripción: Inicia una nueva partida.
- Ejemplo de uso: `/jugar`
- Parámetros opcionales: `size` (número de casillas), `numFlags` (número de minas).
- Ejemplo de uso: `/jugar/{size}/{numFlags}`

#### GET `/ranking`
- Descripción: Obtiene el ranking de partidas.
- Ejemplo de uso: `/ranking`

#### GET `/surrender`
- Descripción: Abandona la partida actual.
- Ejemplo de uso: `/surrender`

#### POST `/jugar`
- Descripción: Descubre una casilla en la partida.
- Ejemplo de uso: `/jugar`
- Parámetros requeridos: `pos` (Posición a descubrir).

### Otras Rutas

#### POST `/signup`
- Descripción: Registra un nuevo usuario.
- Ejemplo de uso: `/signup`
- Parámetros requeridos: `user` (Datos del usuario).

#### POST `/password`
- Descripción: Permite restablecer la contraseña.
- Ejemplo de uso: `/password`
- Parámetros requeridos: `getNewPassword` (Datos de restablecimiento).

### Respuestas del Servidor

El servidor responde a las solicitudes con los siguientes códigos de estado y mensajes:

- Código de estado 404: `ROUTE NOT FOUND` (Ruta no encontrada).
- Código de estado 405: `METHOD NOT SUPPORTED YET` (Método no admitido).
- Código de estado 402: `PARAMETER REQUIRED` (Parámetro requerido).

## 🤝 Contribución

¡Contribuciones son bienvenidas! Si deseas mejorar este proyecto, sigue estos pasos:

1. Haz un fork del repositorio.

2. Crea una rama para tu nueva función: `git checkout -b feature/nueva-funcion`.

3. Realiza tus cambios y asegúrate de seguir las buenas prácticas de codificación.

4. Haz un pull request a la rama principal del proyecto.

5. Espera a que revisemos y fusionemos tus cambios. ¡Gracias por tu contribución!

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Consulta el archivo [LICENSE](LICENSE) para obtener más detalles.

¡Diviértete jugando al Buscaminas en línea! 🕹️
