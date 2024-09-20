# API Demo - Laravel 11 Sanctum - MariaDB - phpMyAdmin - Docker  
**Fecha**: 15 de septiembre de 2024  

Esta es una demostración de una API que permite:  
- **Crear farmacias** con latitud y longitud.  
- **Consultar el listado** de farmacias.  
- **Encontrar la farmacia más cercana** ingresando la latitud y longitud de un punto de referencia.

## Arquitectura
El proyecto sigue una arquitectura de **3 capas**:  
- **Controllers**: Se encargan de recibir las solicitudes y enviar las respuestas. No contienen lógica de negocio.  
- **Services**: Aquí se ubica la lógica de negocio.  
- **Repositories**: Administran el acceso a la base de datos.

## Características adicionales
- **Policies**: Se implementan para incrementar la seguridad según el rol o permisos del usuario.
- **Validaciones**: Las reglas de validación de los requests están centralizadas en clases específicas dentro del directorio `Requests`.
- **Manejo de errores**: Se utiliza un manejo de excepciones con bloques `try-catch` para una captura básica de errores.
- **Testing**: Se realizan pruebas para asegurar la calidad del código y del servicio, ubicadas en el directorio `Tests/Feature`.
- **Optimización**: Se utiliza **Query Builder** en lugar de Eloquent para mejorar el rendimiento en el acceso a los datos, ya que es más eficiente en escenarios de APIs.

---

## Servicios Dockerizados
- **MariaDB**: Contenedor para la base de datos (`apipharma-db`).  
- **phpMyAdmin**: Interfaz para administrar la base de datos (`apipharma-dbadmin`).  
- **APP**: Contenedor de la aplicación (`apipharma`).  

-----------------------------------------


**Instalación no productiva, solo para demo de código:**  

APP_DEBUG=true  
SSL no activo  

**Paso 1:**  
Clonar el repositorio.   
Situados en /apidemo, desde la consola ejecutar el siguiente comando, el cual creara las carpeta "db" (volumen mariadb) y levantará los contenedores de los tres servicios antes mencionados.

**mkdir -p db && docker-compose up -d**  

-----------------------------------------

**Paso 2:**
Luego renombrar el file .env.example a .env y agregar el acceso a la base de datos:

DB_CONNECTION=mysql  
DB_HOST=apipharma-db  
DB_PORT=3310  
DB_DATABASE=apipharma  
DB_USERNAME=apipharma  
DB_PASSWORD=00000000 

-----------------------------------------

**Paso 3:**  

Ingresamos al contenedor de la app:

**$ docker exec -it apipharma bash**  

Corremos comandos para habilitar permisos de escritura  

**# chmod -R 775 bootstrap/**  
**# chmod -R 775 storage/**  

Al construir el contenedor se da de alta un usuario no root (appuser), el cual es necesario en el contenedor apipharma para correr comandos artisan.
Ingresar al contenedor de la app y con usuario appuser correr el comando para instalar dependencias:

**# su appuser**   

Ya con usuario appuser (no usar root), ejecutamos:   

**$ composer install** 

Nota: si el proceso pide correr migraciones, hacerlo.

Verificamos que accedemos a artisan:

**$ php artisan**  

Verificamos que accedemos a la DB, dentro del contenedor ejecutamos:   

**$ php artisan tinker**  
**use Illuminate\Support\Facades\DB; DB::connection()->getPdo();**  

-----------------------------------------

**Paso 4:**  
Si no fueron ejecutadas previamente, ingresar al contenedor de la app y con usuario appuser correr el comando para correr migraciones:

**$ php artisan migrate**   

En este punto el servicio ha sido instalado.  

------------------------------------------------------------------------
Para mayor comodidad, accedemos a phpMyAdmin para ver las tablas creadas:
El contenedor de phpMyAdmin se visualiza en http://localhost:97/  accediendo con:   

host: apipharma-db  
usuario: apipharma   
pass: 00000000    

--------------------------------------

**USO DEL SERVICIO.**  

Desde Postman o cualquier otro cliente HTTP, accedemos al servicio.  
Recuerde que debe crear primero su usuario (/register) para obtener el token y con el mismo acceder a los endpoints.


**ENDPOINT DISPONIBLES EN EL SERVICIO:**

Nota1: Se adjuntan imagenes de vistas postman para el consumo del servicio. (ver carpeta IMAGES)  
Nota2: Se adjunta el pharmacies.sql si se desea importar 3 farmacias de ejemplo.  


Base URL: http://127.0.0.1:83/api/  

Rutas Api disponibles:

POST http://127.0.0.1:83/api/register

POST http://127.0.0.1:83/api/login

GET http://127.0.0.1:83/api/pharmacy/pharmacy-latitude-longitude

GET http://127.0.0.1:83/api/pharmacies-show

POST http://127.0.0.1:83/api/pharmacies-store  

--------------------------------------------------

Ejemplo register:  

http://127.0.0.1:83/api/register  


Accept: application/json  

{
    "name": "Arturo",  
    "email": "email@email.com",  
    "password": "tutte2024",  
    "password_confirmation": "tutte2024"  
}

que obtiene:  

{  
    "token": "12|u8MjsDNEkckoMPkdXdbMv48Qhp0C0lXcWoWfYItZ36f933a7"  
}


---------------------------------------------------

**CREAR FARMACIA:**

POST http://127.0.0.1:83/api/pharmacies-store

HEADERS:  
Accept: application/json  

AUTH:   
Bearer token  

BODY:  

{  
        "nombre": "Farmacia Nairobi",  
        "direccion": "Kenia",  
        "latitud": "-1.2864",  
        "longitud": "36.8172"  
}

---------------------------------------------------

**OBTENER UNA FARMACIA POR ID:**

GET http://127.0.0.1:83/api/pharmacies-show/71

HEADERS:  
Accept: application/json  
Authorization: Bearer token  

---------------------------------------------------

**BUSCAR FARMACIAS CERCANAS A UNA LATITUD Y LONGITUD:**  

GET http://127.0.0.1:83/api/pharmacy-latitude-longitude/19.4326/-99.1332

HEADERS:  
Accept: application/json  
Authorization: Bearer token  

---------------------------------------------------


**TESTING PHP UNIT:**  
Ingresar al contenedor de la app y con usuario appuser correr el comando para test:
Debe existir como minimo un usuario para poder correr los test.


**$ php artisan test** 

o para correr test especificos:    

**$ php artisan test --filter test_pharmacy_creation_ok**  
**$ php artisan test --filter test_pharmacy_creation_with_error_missing_values**  
**$ php artisan test --filter test_find_one_pharmacy**  
