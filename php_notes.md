# Notes on the PHP 
This file will contain some features and methods of the php that is good to know.

## Project Specific
#### Configuration
* Every file should begin with a PHP snippet that `include`s the [config.php](config.php) file and calls startDefaultSessionWith()
e.g.
```
<?php 
    include_once("../config.php");
    startDefaultSessionWith();
    ?>
```
#### Sessions
* Use the ``startDefaultSessionWith()`` method defined in config.php to start a session.
* Use the `getDatabaseConnection()` method defined in config.php to establish a Database connexion (it initializes a PDO object).

#### Path
* `rootDirectory()` method does return the absolute path pf the project root, so that one can refer to other files using it.
However one should be very careful not to use it when writing URLs in which case the user would see the absolute path in the server.


## Variables
Every variable starts with the `$` sign:  
e.g. `$var`, `$lol`

## String
There are two(in fact 4) types of String declaration, which behaves differently:  
>single quoted 'example'\
>double quoted "example"

difference is that writing
```
$var = 'lol';
echo 'var is: $var';
echo "\n<br>\n"; // just prints new line
echo "var is: $var";

``` 
gives the output
>var is: $var  
var is: lol

## Functions
sample definition  
`public function getTableName(): string {}`

## Strict (type enforcement)
!todo
declare(strict_types=1)
url: https://www.php.net/manual/en/language.types.declarations.php#language.types.declarations.strict

## var_dump()
De facto debugger method.
It prints every attribute of the variable given as argument 

## $_SESSION
todo

## $POST
todo