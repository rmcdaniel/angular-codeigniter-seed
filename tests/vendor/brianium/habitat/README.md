Habitat
=======
> Because we dont always know if $_ENV is available

Habitat is a light weight wrapper over PHP's environment variable
functionality. Habitat allows the retrieval of all environment variables
when the ini setting `variables_order` does not contain the `E` flag

Examples
--------

Habitat delegates most functionality to the native php functions `getenv` and `putenv`.
If $_ENV is not available due to the `variables_order` ini setting, then Habitat's `getAll`
method will still allow access to all variables that are in `$_ENV`.

```php
use Habitat\Habit;

//equivalent of getenv('FOO')
$foo = Habitat::getenv('FOO');

//equivalent of putenv('FOO=BAR')
$wasSet = Habitat::putenv('FOO=BAR');

/**
 * Returns $_ENV if set, otherwise set $_ENV via phpinfo() and
 * return it
 */
$all = Habitat::getAll();
```

Parsers
-------
Habitat can support the cli and html varieties of `phpinfo`. It will detect the current sapi
and use the appropriate method to populate $_ENV
