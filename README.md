# JSON Helper

This library provides helper functions for working with json.

### Installing

Require this package, with [Composer](https://getcomposer.org/), in the root directory of your project.

``` bash
$ composer require sanchescom/json-helper
```

## Usage

``` php
<?php

require_once './vendor/autoload.php';

use Sanchescom\Support\Json;
use Sanchescom\Support\Exceptions\JsonException;

class Character {
    public $name;
}

try {
    /**
     *  stdClass Object
     *  (
     *      [name] => Tom
     *  )
     */
    $json = Json::decode('{"name": "Tom"}');

    /**
     *  Array
     *  (
     *      [name] => Tom
     *  )
     */
    $array = Json::asArray('{"name": "Tom"}');

    /**
     *  Character Object
     *  (
     *      [name] => Tom
     *  )
     */
    $instance = Json::asInstanceOf(Character::class, '{"name": "Tom"}');

    /**
     *  Array
     *  (
     *      [0] => Character Object
     *      (
     *          [name] => Tom
     *      )
     *      [1] => Character Object
     *      (
     *          [name] => Jerry
     *      )
     * )
     */
    $collection = Json::asCollectionOfInstances(Character::class, '[{"name": "Tom"}, {"name": "Jerry"}]');

    /**
     * $valid = true
     */
    $valid = Json::isValid('{"data":"Hello World"}');
    
    /**
     * $valid = false
     */
    $invalid = Json::isValid('{1}');
} catch (JsonException $e) {
    echo $e->getMessage();
} catch (ReflectionException $e) {
    echo $e->getMessage();
}
```

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/sanchescom/php-wifi/tags). 

## Authors

* **Efimov Aleksandr** - *Initial work* - [Sanchescom](https://github.com/sanchescom)

See also the list of [contributors](https://github.com/sanchescom/php-wifi/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details