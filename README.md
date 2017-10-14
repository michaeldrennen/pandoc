# pandoc
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

###With exception handling
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