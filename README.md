# siscofac
Sistema para la Gestión de Contratos y Facturas

## Instalación
1.	Instalar como mínimo `Apache ^2.4.27`, `Php ^7.1.9` y `MariaDB ^10.2.8`
2.	Instalar [Git](https://git-scm.com/downloads)
3.	Instalar [Composer](https://getcomposer.org/download/)
4.	Instalar [Node JS](https://nodejs.org/es/download/)
5.	Acceder a la carpeta **pública** del servidor web. Ejemplo: `www` `htdocs`
6.	Crear un nuevo proyecto de `symfony` de tipo `website-skeleton`
```
composer create-project symfony/website-skeleton siscofac
```
7.	Clonar el proyecto `siscofac` en la ruta pública del servidor web
```
git clone --no-checkout https://github.com/michaelrodriguezalvarez/siscofac.git siscofac/siscofac.tmp
```
8.	Mover `siscofac/siscofac.tmp/.git` hacia `siscofac/`
9.	Eliminar `siscofac/siscofac.tmp`
10.	Acceder a `siscofac`
11.	Realizar un hard reset con git para cargar los ficheros del proyecto
```
git reset --hard HEAD
```
12.	Actualizar e Instalar dependencias con `composer`
```
composer update
```
13.	Instalar `encore/webpack`
```
npm install
```
14.	Compilar los `assets` hacia `public/build`
```
"node_modules/.bin/encore.cmd" dev
```
15.	Configurar la base de datos
- Abrir el fichero `.env`
- Modificar la línea `DATABASE_URL=mysql://root@127.0.0.1:3307/siscofac`
16.	Crear la base de datos con `doctrine`
```
php bin/console doctrine:database:create
```
17.	Crear las tablas de la base de datos con `doctrine`
```
php bin/console doctrine:schema:create
```
18.	Acceder desde un navegador web a la `url` del proyecto, por ejemplo: [http://localhost/siscofac/public/index.php/principal](http://localhost/siscofac/public/index.php/principal)

>Este fichero fue creado con la ayuda de [Markdown Editor](https://jbt.github.io/markdown-editor/).