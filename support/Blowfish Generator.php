<?php
// Backup do site
ini_set('display_errors',1); ini_set('display_startup_erros',1); error_reporting(E_ALL);//force php to show any error message


function generateBcryptHash(string $pass, int $cust = 10, ?string $salt = null) 
{
    if (null === $salt) {
        $salt = 'Cf1f11ePArKlBJomM0F6aJ';  // Default salt
    }
    return crypt($pass, '$2a$' . $cust . '$' . $salt . '$');
}


var_dump(
    generateBcryptHash('', 10, 'Cf1f11ePArKlBJomM0F6aJ')
);