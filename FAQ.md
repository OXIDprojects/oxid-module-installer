# Problems we had while developing

## There are no registered paths for namespace "__main__"

Means u want to render template `Path/index.html.twig` insteat of `@BundleName/Path/index.html.twig`

`@BundleName` must be configured into bundle root class

## Twig output has crazy whitespaces and tabs

U have to use valid html. Text written directly into BODY will be wrapped with PRE-Tag.

## Package / Klass not found in PHAR file

Try to run `composer update` before using `phing debug`. 