# SSO Driver
This is private package (for now) used for PT Mumtaz Teknologi Indonesia as driver to integration with SSO. This package will help you with almost zero configuration for authentication. You just need to set environtment variable for credential client and then pass credential user for authentication method.



## Installation
You can install this package with additional repository, because this package is not published on packagist. You can add this on your composer json to add source of your composer from github.
```json
"repositories" : {
  {
      "type": "vcs",
      "url": "git@github.com:classid/sso-driver.git"
  }
}
```

After that you can install this package :
```sh
composer require classid/sso-driver
```

You can publish configuration file via this commnd :
```sh
php artisan vendor:publish --provider="Classid\SsoDriver\MumtazSSODriverServiceProvider" 
```

## How to use ?

```php
<?php
(new Classid\SsoDriver\Services\AuthService())->authenticate([
  "usernaame" => "this_is_usernme",
  "password" => "password",
  "institution_id" => "9b9d1b44-bfb7-4c19-a3c7-0a4db2adf900"
]);
```

The response would be like this : (depend on version, could be changed in the future)

```json
{
    "rc": "SUCCESS",
    "message": "Authentication user successfully !",
    "timestamp": "2023-10-26T23:49:47.387526Z",
    "payload": {
        "data": {
            "id": "99ffccb6-b375-4cf5-9f4c-b6824fabeab3",
            "name": "user finance 1",
            "username": "username_finance_1",
            "institutions": [
                {
                    "id": "99ffccb6-b375-4cf5-9f4c-b6824fabeab7",
                    "parent_id": null,
                    "name": "Institution A Finance"
                }
            ]
        }
    }
}
```
