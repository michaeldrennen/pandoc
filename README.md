# pandoc

[![Latest Stable Version](https://poser.pugx.org/michaeldrennen/pandoc/v/stable)](https://packagist.org/packages/michaeldrennen/pandoc) [![Total Downloads](https://poser.pugx.org/michaeldrennen/pandoc/downloads)](https://packagist.org/packages/michaeldrennen/pandoc) [![License](https://poser.pugx.org/michaeldrennen/pandoc/license)](https://packagist.org/packages/michaeldrennen/pandoc) [![Coverage Status](https://coveralls.io/repos/github/michaeldrennen/pandoc/badge.svg?branch=master)](https://coveralls.io/github/michaeldrennen/pandoc?branch=master) [![Build Status](https://travis-ci.org/michaeldrennen/pandoc.svg?branch=master)](https://travis-ci.org/michaeldrennen/pandoc) 

PHP 7 compatible wrapper around Pandoc (https://github.com/jgm/pandoc)

## Usage

### From file -> To file
```php
$pandoc = new Pandoc();
$pandoc->fromFile('./from.txt')
       ->toFile('./to.docx')
       ->convert();
```

### String content -> To file
```php
$pandoc = new Pandoc();
$pandoc->content('<p>Wow, I really Cronenberged up the whole place, huh Morty?</p>')
       ->toFile('./to.docx')
       ->convert();
```

## With exception handling
```php
try{
    $pandoc = new Pandoc();
    $pandoc->fromFile('./from.txt')
           ->toFile('./to.docx')
           ->convert();
} catch(PandocException $exception) {
    echo $exception->getMessage();
}

```