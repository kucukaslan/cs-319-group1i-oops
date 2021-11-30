# PHP Notes
This file will contain some features and methods of the php that is good to know.

## Variables
Every variable starts with the `$` sign:  
e.g. `$var`, `$lol`

## String
There are two(in fact 4) types of of String declaration, which behaves differently:  
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