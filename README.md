#  RIOT GAMES PAGE

### It will be a page that will show each character, skills, synergies with other characters etc...

Configuramos la base de datos en el fichero `.env.local`, por ejemplo:
```
###> symfony/framework-bundle ###
APP_ENV=dev
APP_DEBUG=1
###< symfony/framework-bundle ###

###> doctrine/doctrine-bundle ###
DATABASE_URL="mysql://root@localhost:3306/riot_page"
###< doctrine/doctrine-bundle ###
```

Instalamos las dependencias de Symfony:
```
composer install
```

Creamos la base de datos, el schema y las fixtures:
```
composer database
composer schema
composer fixtures
```

Instalamos las dependencias de yarn:
```
yarn
```

Y para iniciar el proyecto lo hacemos con:
```
symfony server:start
```
Obtendrá la versión de php de `.php-version` y los parámetros de `.symfony.local.yaml`

