## Api Demo - Laravel 11 Sanctum - MariaDB - phpMyAdmin - Docker  
##### - 15-Sept-2024

Demo de API que permite crear farmacias con latitud y longitud, consultar el listado de las mismas y encontrar la mas cercana ingresando latitud y longitud de un punto inicial.  
Arquitectura de 3 capas: CONTROLLERS, SERVICES Y REPOSITORIES. 

- Los Controladores se utilizan para recibir y responder, no contienen lógica de negocio.  
- Services contiene la lógica pura de negocio.  
- Repositories accede al modelo de base de datos.  

Se utilizan POLICIES donde es posible incrementar la seguridad según alcance del usuario.  
Las validaciones de request se personalizan en clases aparte, carpeta REQUESTS.  
Se utilizan try-catch básico para el manejo simple de errores.  
Se realizan test para asegurar la calidad del código y funcionamiento del servicio, carpeta TESTS/FEATURE  
Se utiliza Query Builder en vez de Eloquent, ya que el primero es más rapido, lo cual mejora la performance para servicios API.

------------------------------------------------------------------------

Servicios a crear en ambiente dockerizado:

MariaDB     --> apipharma-db  
phpMyAdmin  --> apipharma-dbadmin  
APP         --> apipharma  


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

GET http://127.0.0.1:83/api/pharmacy

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

GET http://127.0.0.1:83/api/pharmacy/19.4326/-99.1332 

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
