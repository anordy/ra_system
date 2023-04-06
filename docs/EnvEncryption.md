# Encrypting Environment Variables.
Environment variables are a crucial part of any software application, as they affect the way the application runs. They contain different parameters and configurations such as database, email, SMS, queue connections, etc., which are required for the application to function properly.

Due to the fact that environment variables contain sensitive information that is of interest to attackers, we have implemented an encryption mechanism to ensure that the credentials are not compromised in case the file is obtained by an attacker. In this way, the attacker will not be able to access the plain credentials.

## Artifacts
The artifact are found in
- **app/Services/EncryptEnv** directory contains all the core classes of the encryption service.
- **app/Console/Command/Env** directory contains commands to encrypt the environment variables
- **app/Helpers/secEnv** file expose environment encryption functions on the global scope
- **config/envsecure** file exposes the default configuration require by the encryption service


## Configurations
- Specify the value of *encrypt_flag* specified under **envsecure** config file on the environment variable you wish to be encrypted. The variable with encrypt_flag value should look like this DB_PASSWORD=!UBX:password the *!UBX:* represent encrypt_flag and values after that represent the actual password.
- Change from env() to secEnv() function where you are calling given environment eg. DB_PASSWORD variable is configured on app/database.php  hence you should change  'password' => env('DB_PASSWORD', '') to  'password' => secEnv('DB_PASSWORD', '') in order for decryption of the variable to be done otherwise you will keep getting errors.
- To confirm if the encryption has happened the environment variable will contain ENC: prefix eg. ENC:eyJpdiI6ImVWYjFaOEV....

## Encrypting
In order to encrypt the environment variable run **php artisan env:encrypt {configkey?}** command in the project root. 
The command have the following vairations
-  **php artisan env:encrypt*** running this command requires you to specify the existing/new key to encrypt the environment variable and point to note, if you will be supplying your own keys the length of the key should be considered with respect to the algorithm used as follows,
    - aes-128-cbs algorithm the string length of the key should be 16
    - aes-256-cbs algorithm the string length of the key should be 33
- **php artisan env:encrypt generate-key*** running this command will select for you the algorthim configured in the config file and behind the scene it will encrypt the env file and provide you with the encryption key which should be protected.


## Config file
Config file **envsecure.php** used to configure the encryption service and determine the behavior of the service.
```php
return [
    'cipher' => 'AES-128-CBC',
    'encrypt_flag' => '!UBX:',
    'custom_config_file' => '',
    'custom_config_output' => 'env',
];
```
Descriptions of keys.
- `ciphere` used to determine which algorithm is used to encrypt file currently supported are AES-128-CBC and AES-256-CBC
- `encrypt_flag` used to encrypt the env variable with the prefix specified in this tag. For this to work you need to add the prefix defined here in the given environment in order for it to be encrypted example DB_PASSWORD=!UBX:password 
- `custom_config_file` used to determine the custom file that service will read and encrypt the variable default it will take .env
- `custom_config_output` used to determine the output file that will be written after the encryption is done.

## Helper file
Service helper file secEnv.php should be registered under **autoload** in the **composer.json** so that function *secEnv()** will be available with the project for usage otherwise you will get the error of the method not found.
Below is how to register the file on the composer.json file.
```php
"autoload": {
    "files": [
        "app/Helpers/secEnv.php",
    ]
},
```