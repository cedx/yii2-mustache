# Mustache.yii
[![Version](http://img.shields.io/packagist/v/cedx/mustache-yii.svg?style=flat)](https://packagist.org/packages/cedx/mustache-yii) [![Downloads](http://img.shields.io/packagist/dt/cedx/mustache-yii.svg?style=flat)](https://packagist.org/packages/cedx/mustache-yii) [![License](http://img.shields.io/packagist/l/cedx/mustache-yii.svg?style=flat)](https://github.com/cedx/mustache.yii/blob/master/LICENSE.txt)

[Mustache](http://mustache.github.io) templating for [Yii](http://www.yiiframework.com), high-performance [PHP](https://php.net) framework.

## Documentation
- [API Reference](http://dev.belin.io/mustache.yii/api)

## Installing via [Composer](https://getcomposer.org)

#### 1. Depend on it
Add this to your project's `composer.json` file:

```json
{
  "require": {
    "cedx/mustache-yii": "*"
  }
}
```

#### 2. Install it
From the command line, run:

```shell
$ php composer.phar install
```

#### 3. Import it
Now in your application configuration file, you can use the following view renderer:

```php
return [
  'aliases'=>[
    'mustache'=>'ext.cedx.mustache-yii.lib',
  ],
  'components'=>[
    'viewRenderer'=>[
      'class'=>'mustache.CMustacheViewRenderer'
    ]
  ]
];
```

Adjust the values as needed. Here, it's supposed that [`CApplication->extensionPath`](http://www.yiiframework.com/doc/api/1.1/CApplication#extensionPath-detail), that is the [`ext`](http://www.yiiframework.com/doc/guide/1.1/en/basics.namespace) root alias, has been set to Composer's `vendor` directory.

## License
[Mustache.yii](https://packagist.org/packages/cedx/mustache-yii) is distributed under the MIT License.
