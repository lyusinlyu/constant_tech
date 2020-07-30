# constant_tech

This application utilizes the following frameworks and tools.

* [Laravel](http://laravel.com) (Backend)
* [VueJS](https://vuejs.org/) (Frontend)
* [Sass](http://sass-lang.com/) (SCSS)
* [Webpack](https://webpack.js.org/) (Toolkit)

## Getting Started ##


1) Copy **.env.example** to **.env** and add your database information there

2) Run the following commands

```
#!terminal

composer install
```

```
#!terminal
php artisan key:generate
```

3) JWTAuth: Add the service provider to the providers array in the config/app.php config file as follows:

```
'providers' => [

    Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
]
```

Publish the config
```
#!terminal
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
```

Generate secret key
```
#!terminal
php artisan jwt:secret
```

Start web server
```
#!terminal
php artisan serve
```

4) You should now be up and running on localhost:8000

## Front-End Development, Sass, Js & LiveReload ##

1) In Terminal navigate to the / directory and run the following command
```
#!terminal

npm install
```

2) Start npm task with
```
#!terminal

npm run dev
```

3) Now Sass & Js should compile

