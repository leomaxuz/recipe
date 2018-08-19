# Recipe

Recipe directory app

## Getting Started

### Installation

It's recommended that you use [Composer](https://getcomposer.org/) to install Slim.
```
$ composer require slim/slim "^3.0"
```

### Usage

Create an index.php file with the following contents:
```php
<?php
session_start();
require 'vendor/autoload.php';
include 'config.php';
$app = new Slim\App(["settings" => $config]);
//Обработка
$container = $app->getContainer();

$container['db'] = function ($c) {
   try{
       $db = $c['settings']['db'];
       $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
       PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
       );
       $pdo = new PDO("mysql:host=" . $db['servername'] . ";dbname=" . $db['dbname'], $db['username'], $db['password'],$options);
       return $pdo;
   }
   catch(\Exception $ex){
       return $ex->getMessage();
   }
   
};

include 'api/user.php';
include 'api/recipe.php';

$app->run();
```

Create an confg.php file with the following contents:
```php
<?php
$config = [
  'db' => [
     'servername' =>'THE HOST NAME',
     'username' => 'USER USER NAME',
     'password' => 'THE PASSWORD ',
     'dbname' => 'THE DATABASE NAME',
  ]
];
```

Create an .htaccess file with the following contents:
```
RewriteEngine On
RewriteCond %{Request_Filename} !-f
RewriteCond %{Request_Filename} !-d
RewriteRule ^ index.php [QSA,L]
```

## Running the recipe app

### Api information
API http://your-site/ attribute / parameters

### API USER
Sign up
```
Method: POST
Attribute: signup
Parameters: username,email,password
```

Log in
```
Method: GET
Attribute: login 
Parameters: username,password
```

Sign out
```
Method: GET
Attribute: signout 
Parameters: no
```

View user
```
Method: GET
Attribute: user{id} 
Parameters: no
```

View users
```
Method: GET
Attribute: users
Parameters: no
```

Update user
```
Method: PUT
Attribute: user
Parameters: username,email,password
````

Delete user
```
Method: DELETE
Attribute: user
Parameters: no
```

### API RECIPE
Create recipe
```
Method: POST
Attribute: recipe
Parameters: name,description,image_link
```

View recipe
```
Method: GET
Attribute: recipe{id} 
Parameters: no
```

View recipes
```
Method: GET
Attribute: recipes
Parameters: no
```

Update recipe
```
Method: PUT
Attribute: recipe{id} 
Parameters: name,description,image_link
````

Delete recipe
```
Method: DELETE
Attribute: recipe{id}
Parameters: no
```
