# Icon Service

Managing icons for PHP applications.

## Table of Contents

- [Getting started](#getting-started)
    - [Requirements](#requirements)
    - [Highlights](#highlights)
- [Documentation](#documentation)
    - [Icon Interface](#icon-interface)
        - [Icon](#icon)
    - [Icon Factory Interface](#icon-factory-interface)
        - [Icon Factory](#icon-factory)
        - [Icon Factory Translator](#icon-factory-translator)
    - [Icons Interface](#icons-interface)
        - [Svg File Icons](#svg-file-icons)
        - [Svg File Icons To Json File](#svg-file-icons-to-json-file)
        - [Svg File Icons To Json Files](#svg-file-icons-to-json-files)
        - [In Memory Html Icons](#in-memory-html-icons)
        - [Icons](#icons)
        - [Stack Icons](#stack-icons)
    - [Example](#example)
        - [Font Awesome](#font-awesome)
- [Credits](#credits)
___

# Getting started

Add the latest version of the icon service project running this command.

```
composer require tobento/service-icon
```

## Requirements

- PHP 8.0 or greater

## Highlights

- Framework-agnostic, will work with any project
- Decoupled design
- Customizable with factories to fit your needs

# Documentation

## Icon Interface

```php
use Tobento\Service\Icon\IconInterface;
use Tobento\Service\Icon\IconFactory;

$icon = (new IconFactory())->createIconFromHtml('download', '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 100 100"><path d="M0,100H100V90H0ZM100,50H66.67V0H33.33V50H0L50,83.33Z"/></svg>');

var_dump($icon instanceof IconInterface);
// bool(true)
```

**name**

Returns the icon name.

```php
var_dump($icon->name());
// string(8) "download"
```

**render**

Returns the icon.

```php
<?= $icon->render() ?>

// or just
<?= $icon ?>
```

Both icons from above will produce the following output:

```html
<span class="icon icon-download">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 100 100">
        <path d="M0,100H100V90H0ZM100,50H66.67V0H33.33V50H0L50,83.33Z"/>
    </svg>
</span>
```

**size**

Returns a new instance with the specified size.

```php
$icon = $icon->size('xs');
```

**attr**

Returns a new instance with the specified attribute.

```php
$icon = $icon->attr(name: 'id', 'some-id');
```

Adds a class to existing classes:

```php
$icon = $icon->attr(name: 'class', 'foo');
```

Overwrites existing classes:

```php
$icon = $icon->attr(name: 'class', ['foo']);
```

**label**

Returns a new instance with the specified label and position.

```php
$icon = $icon->label(text: 'Download');

$icon = $icon->label(text: 'Download', position: 'left');
```

**labelSize**

Returns a new instance with the specified label size.

```php
$icon = $icon->labelSize('xl');
```

**labelAttr**

Returns a new instance with the specified label size.

```php
$icon = $icon->labelAttr(name: 'id', 'some-id');
```

Adds a class to existing classes:

```php
$icon = $icon->labelAttr(name: 'class', 'foo');
```

Overwrites existing classes:

```php
$icon = $icon->labelAttr(name: 'class', ['foo']);
```

**tag**

Returns a new instance of the icon tag.

```php
use Tobento\Service\Tag\TagInterface;

var_dump($icon->tag() instanceof TagInterface);
// bool(true)
```

Check out [Tag Interface](https://github.com/tobento-ch/service-tag#tag-interface) to learn more about the interface.

### Icon

```php
use Tobento\Service\Icon\Icon;
use Tobento\Service\Icon\IconInterface;
use Tobento\Service\Tag\Tag;
use Tobento\Service\Tag\TagInterface;
use Tobento\Service\Tag\Attributes;

$icon = new Icon(
    name: 'download',
    tag: new Tag(
        name: 'svg',
        html: '<path d="M0,100H100V90H0ZM100,50H66.67V0H33.33V50H0L50,83.33Z"/>',
        attributes: new Attributes([
            'xmlns' => 'http://www.w3.org/2000/svg',
            'width' => '20',
            'height'=> '20',
            'viewBox' => '0 0 100 100',
        ]),
    ),
    labelTag: null, // null|TagInterface
    parentTag: null, // null|TagInterface
);

var_dump($icon instanceof IconInterface);
// bool(true)
```

You may check out the [Tag Service](https://github.com/tobento-ch/service-tag) to learn more about it.

## Icon Factory Interface

Easily create icons with the provided icon factory:

```php
use Tobento\Service\Icon\IconFactoryInterface;
use Tobento\Service\Icon\IconFactory;

$iconFactory = new IconFactory();

var_dump($iconFactory instanceof IconFactoryInterface);
// bool(true)
```

**createIcon**

```php
use Tobento\Service\Icon\IconInterface;
use Tobento\Service\Icon\CreateIconException;
use Tobento\Service\Tag\Tag;
use Tobento\Service\Tag\TagInterface;
use Tobento\Service\Tag\Attributes;

try {
    $icon = $iconFactory->createIcon(
        name: 'download',
        tag: new Tag(
            name: 'svg',
            html: '<path d="M0,100H100V90H0ZM100,50H66.67V0H33.33V50H0L50,83.33Z"/>',
            attributes: new Attributes([
                'xmlns' => 'http://www.w3.org/2000/svg',
                'width' => '20',
                'height'=> '20',
                'viewBox' => '0 0 100 100',
            ]),
        ),
        labelTag: null, // null|TagInterface
        parentTag: null, // null|TagInterface
    );

    var_dump($icon instanceof IconInterface);
    // bool(true)
} catch (CreateIconException $e) {
    // do something
}
```

**createIconFromHtml**

```php
use Tobento\Service\Icon\IconInterface;
use Tobento\Service\Icon\CreateIconException;

try {
    $icon = $iconFactory->createIconFromHtml(
        name: 'download',
        html: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 100 100"><path d="M0,100H100V90H0ZM100,50H66.67V0H33.33V50H0L50,83.33Z"/></svg>'
    );

    var_dump($icon instanceof IconInterface);
    // bool(true)
} catch (CreateIconException $e) {
    // do something
}
```

**createIconFromFile**

```php
use Tobento\Service\Icon\IconInterface;
use Tobento\Service\Icon\CreateIconException;
use Tobento\Service\Filesystem\File;

try {
    $icon = $iconFactory->createIconFromFile(
        name: 'download',
        file: 'path/download.svg' // string|File
    );

    var_dump($icon instanceof IconInterface);
    // bool(true)
} catch (CreateIconException $e) {
    // do something
}
```

### Icon Factory

```php
use Tobento\Service\Icon\IconFactoryInterface;
use Tobento\Service\Icon\IconFactory;
use Tobento\Service\Tag\TagFactoryInterface;

$iconFactory = new IconFactory(
    tagFactory: null // null|TagFactoryInterface
);

var_dump($iconFactory instanceof IconFactoryInterface);
// bool(true)
```

The default icon factory will produce the following icon output:

```php
$icon = $iconFactory->createIconFromHtml(
    name: 'download',
    html: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 100 100"><path d="M0,100H100V90H0ZM100,50H66.67V0H33.33V50H0L50,83.33Z"/></svg>'
);
    
<?= $icon->size('xs')->label('Download')->labelSize('xl') ?>
```

output:

```html
<span class="icon icon-download text-xs">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 100 100">
        <path d="M0,100H100V90H0ZM100,50H66.67V0H33.33V50H0L50,83.33Z"/>
    </svg>
    <span class="icon-label text-xl">Download</span>
</span>
```

**size class map**

You might create your own factory to adjust css sizes for your needs:

```php
class MyIconFactory extends IconFactory
{
    protected array $sizeClassMap = [
        'xxs' => 'text-xxs',
        'xs' => 'text-xs',
        's' => 'text-s',
        'm' => 'text-m',
        'l' => 'text-l',
        'xl' => 'text-xl',
        'xxl' => 'text-xxl',
        'body' => 'text-body',
    ];
}
```

### Icon Factory Translator

Translates the svg ```<title></title>``` tag. You might enhance it for your needs though.

```
composer require tobento/service-translation
```

You may check out the [Translation Service](https://github.com/tobento-ch/service-translation) to learn more about it.

```php
use Tobento\Service\Translation\Translator;
use Tobento\Service\Translation\Resources;
use Tobento\Service\Translation\Resource;
use Tobento\Service\Translation\Modifiers;
use Tobento\Service\Translation\MissingTranslationHandler;
use Tobento\Service\Icon\IconFactoryTranslator;
use Tobento\Service\Icon\SvgFileIcons;
use Tobento\Service\Dir\Dirs;
use Tobento\Service\Dir\Dir;

$translator = new Translator(
    new Resources(
        new Resource('*', 'de', [
            'File' => 'Datei',
            'Shopping Cart' => 'Warenkorb',
        ]),
    ),
    new Modifiers(),
    new MissingTranslationHandler(),
    'de',
);

$icons = new SvgFileIcons(
    dirs: new Dirs(new Dir('private/svg-icons/')),
    iconFactory: new IconFactoryTranslator($translator),
);
```

## Icons Interface

```php
use Tobento\Service\Icon\IconsInterface;
use Tobento\Service\Icon\Icons;
use Tobento\Service\Icon\IconFactory;

$icons = new Icons(new IconFactory());

var_dump($icons instanceof IconsInterface);
// bool(true)
```

**get**

```php
use Tobento\Service\Icon\IconInterface;
use Tobento\Service\Icon\IconNotFoundException;

try {
    $icon = $icons->get('download');

    var_dump($icon instanceof IconInterface);
    // bool(true)
} catch (IconNotFoundException $e) {
    // do something
}
```

**has**

```php
var_dump($icons->has('download'));
// bool(true)
```

### Svg File Icons

This class creates icons from svg files.

```
private/
    svg-icons/
        download.svg
        ...
```

```php
use Tobento\Service\Icon\IconsInterface;
use Tobento\Service\Icon\SvgFileIcons;
use Tobento\Service\Dir\Dirs;
use Tobento\Service\Dir\Dir;

$icons = new SvgFileIcons(
    dirs: new Dirs(new Dir('private/svg-icons/'))
);

var_dump($icons instanceof IconsInterface);
// bool(true)
```

### Svg File Icons To Json File

This class creates icons from svg files but caches all in one json file.

```php
use Tobento\Service\Icon\IconsInterface;
use Tobento\Service\Icon\SvgFileIconsToJsonFile;
use Tobento\Service\Dir\Dirs;
use Tobento\Service\Dir\Dir;

$icons = new SvgFileIconsToJsonFile(
    dirs: new Dirs(new Dir('private/svg-icons/')),
    cacheDir: new Dir('private/svg-icons/'),
    clearCache: false, // set true to clear cache.
);

var_dump($icons instanceof IconsInterface);
// bool(true)
```

### Svg File Icons To Json Files

This class creates icons from svg files but caches each in a json file.

```php
use Tobento\Service\Icon\IconsInterface;
use Tobento\Service\Icon\SvgFileIconsToJsonFiles;
use Tobento\Service\Dir\Dirs;
use Tobento\Service\Dir\Dir;

$icons = new SvgFileIconsToJsonFiles(
    dirs: new Dirs(new Dir('private/svg-icons/')),
    cacheDir: new Dir('private/svg-icons-json/'),
    clearCache: false, // set true to clear cache.
);

var_dump($icons instanceof IconsInterface);
// bool(true)
```

### In Memory Html Icons

```php
use Tobento\Service\Icon\InMemoryHtmlIcons;
use Tobento\Service\Icon\IconFactory;
use Tobento\Service\Icon\IconsInterface;

$icons = new InMemoryHtmlIcons(
    icons: [
        'download' => 'html',
        'cart' => 'html',
    ],
    iconFactory: new IconFactory(),
);

var_dump($icons instanceof IconsInterface);
// bool(true)
```

### Icons

This icons class might be used for custom factories.

```php
use Tobento\Service\Icon\IconsInterface;
use Tobento\Service\Icon\Icons;
use Tobento\Service\Icon\IconFactory;

$icons = new Icons(
    iconFactory: new IconFactory()
);

var_dump($icons instanceof IconsInterface);
// bool(true)
```

### Stack Icons

The StackIcons class allows combining any number of other icons. If the requested icon does not exist in the first icons collection, the next icons will try and so on.

```php
use Tobento\Service\Icon\StackIcons;
use Tobento\Service\Icon\SvgFileIcons;
use Tobento\Service\Dir\Dirs;
use Tobento\Service\Dir\Dir;
use Tobento\Service\Icon\InMemoryHtmlIcons;
use Tobento\Service\Icon\IconFactory;
use Tobento\Service\Icon\Icons;
use Tobento\Service\Icon\IconsInterface;

$icons = new StackIcons(
    new SvgFileIcons(
        dirs: new Dirs(new Dir('private/svg-icons/'))
    ),
    new InMemoryHtmlIcons(
        icons: [
            'download' => 'html',
            'cart' => 'html',
        ],
        iconFactory: new IconFactory(),
    ),
    // might be set as fallback as not to throw exception
    // when icon is not found.
    new Icons(new IconFactory()),    
);

var_dump($icons instanceof IconsInterface);
// bool(true)
```

## Example

### Font Awesome

This might be a possible way to create font awesome icons:

```php
use Tobento\Service\Icon\IconFactory;
use Tobento\Service\Icon\IconInterface;
use Tobento\Service\Icon\Icons;
use Tobento\Service\Tag\TagInterface;
use Tobento\Service\Tag\Attributes;

$faIconFactory = new class() extends IconFactory
{
    public function createIcon(
        string $name,
        null|TagInterface $tag = null,
        null|TagInterface $labelTag = null,
        null|TagInterface $parentTag = null,
    ): IconInterface {
        
        if (is_null($tag)) {         
            $tag = $this->tagFactory->createTag(
                name: 'i',
                attributes: new Attributes([
                    'class'=> 'fa-solid fa-'.strtolower($name),
                ]),
            );
        }
        
        return parent::createIcon($name, $tag, $labelTag, $parentTag);
    }
};

$icons = new Icons(
    iconFactory: $faIconFactory
);
```

```php
<?= $icons->get('file')->label('File') ?>

<?= $icons->get('file')->tag()->class('foo') ?>
```

output:

```html
<span class="icon icon-file">
    <i class="fa-solid fa-file"></i>
    <span class="icon-label">File</span>
</span>

<i class="fa-solid fa-file foo"></i>
```

# Credits

- [Tobias Strub](https://www.tobento.ch)
- [All Contributors](../../contributors)