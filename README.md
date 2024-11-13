## Requerimientos

- PHP >= 8.1
- Ctype PHP Extension
- cURL PHP Extension
- DOM PHP Extension
- Fileinfo PHP Extension
- Filter PHP Extension
- Hash PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PCRE PHP Extension
- PDO PHP Extension
- Session PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

## Installacion

- Descomprimir zip
- Ejecutar `composer install`
- Renombrar o coiar `.env.example` a `.env`
- Ejecutar `php artisan key:generate`
- Configurar base de datos en `.env`
- Ejecutar `php artisan migrate` para ejecutar las migraciones
- Ejecutar `php artisan db:seed` para crear el usuario administrador

- Credenciales del usuario administrador: 
```
email: admin@twgroup.cl 
password: password
 ```
## Nota

Para el proyecto en local lo desarrolle ejecutandolo en docker

- Si usan docker pueden configurar la base de datos en el docker-compose.yml
- Ejecutar `docker compose up -d`
- Y al terminar de crear la imagen se podra acceder al proyecto en localhost:8000

## Creditos

- Laravel SB Admin 2
- Laravel - Open source framework.
- LaravelEasyNav - Making managing navigation in Laravel easy.
- SB Admin 2 - Thanks to Start Bootstrap.
