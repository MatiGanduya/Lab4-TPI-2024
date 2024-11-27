# Sistema de Turnos

Este proyecto de Laravel permite a los usuarios registrarse y crear empresas con servicios asociados. 
Los usuarios dueños de una empresa o colaboradores tienen un apartado de disponibilidad, donde deben agregar su disponibilidad horaria.
Además, incluye la posibilidad de gestionar colaboradores, quienes son usuarios sin una empresa propia, ni asociada y 
pueden ser invitados por un dueño de empresa para colaborar. 
Cualquier usuario registrado puede solicitar turnos para los servicios ofrecidos.

===================================================================

## Estructura de la Base de Datos

- Modelo Entidad-Relación (MER): https://drive.google.com/file/d/11O8Epc41s3BO9kpTcR8-I6iKTP-SaJfW/view?usp=sharing
- Relación de Tablas: https://drive.google.com/file/d/1ZwbGSQs47OF1-4vCiGuduHKiqYbfcidZ/view?usp=sharing

===================================================================

## Requisitos

Para ejecutar este proyecto, necesitas tener instalados los siguientes componentes:
- PHP 8.1.x
- Laravel 11.x
- Composer
- Node.js

===================================================================

## Configuración

1. Crea un archivo `.env` en la raíz del proyecto utilizando el archivo de ejemplo `.env.example` como referencia.
2. Configura las credenciales de tu base de datos en el archivo `.env`.

===================================================================

## Instalación

Sigue estos pasos para instalar el proyecto:

1. Clona el repositorio en tu máquina local.
2. Ejecuta el comando `composer install` en la raíz del proyecto para instalar las dependencias de PHP.
3. Ejecuta el comando `npm install` en la raíz del proyecto para instalar las dependencias de Node.js.
4. Genera una clave de aplicación única con el comando:
   php artisan key:generate
5. Migra las tablas a la base de datos con el comando:
   php artisan migrate

===================================================================

## Ejecución

Para ejecutar el proyecto en un entorno local:

1. Inicia el servidor de desarrollo de Vite con el comando:
   npm run dev
2. Inicia el servidor de Laravel con el comando:
   php artisan serve
3. Abre tu navegador y accede al proyecto en: http://127.0.0.1:8000

===================================================================


