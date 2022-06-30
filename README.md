# Amos Documenti 

Documenti management.

### Installation
You need to require this package and enable the module in your configuration.

add to composer requirements in composer.json
```
"open20/amos-documenti": "dev-master",
```
or run command
***bash***
```bash
composer require "open20/amos-documenti:dev-master"
```

Enable the Documenti modules in modules-amos.php, add :
```php
 'documenti' => [
	'class' => 'open20\amos\documenti\AmosDocumenti',
 ],

```

add documenti migrations to console modules (console/config/migrations-amos.php):
```
'@vendor/open20/amos-documenti/src/migrations'
```

If a frontend or a public site are used in your project and documenti need to be visible outside backend, enable form/wizard fields to allow publication in frontend/home page with params:
```php
'documenti' => [
        'class' => 'open20\amos\documenti\AmosDocumenti',
        'params' => [
            'site_publish_enabled' => true,
            'site_featured_enabled' => true
        ]
    ],
```

The content is suitable to be used with cwh content management.
To do so:
- Activate cwh plugin
- Open cwh configuration wizard (admin privilege is required) url: <yourPlatformurl>/cwh/configuration/wizard
- search for documenti in content configuration section
- edit configuration of documenti and save

If tags are needed enable this module in "modules-amos.php" (backend/config folder in main project) in tag section. After that, enable the trees in tag manager.

If platform uses report and/or comments and you want to enable Documenti to be commented/to report a content, add the model to the configuration in modules-amos.php:

for reports: 

```
 'report' => [
     'class' => 'open20\amos\report\AmosReport',
     'modelsEnabled' => [
        .
        .
        'open20\amos\documenti\models\Documenti', //line to add
        .
        .
     ]
     ],
```

for comments:

```
  'comments' => [
    'class' => 'open20\amos\comments\AmosComments',
    'modelsEnabled' => [
        .
        .
        'open20\amos\documenti\models\Documenti', //line to add
        .
        .
 	],
  ],
```


### Configurable fields 

Here the list of configurable fields, properties of module AmosDocumenti.
If some property default is not suitable for your project, you can configure it in module, eg: 

```
 'documenti' => [
	'class' => 'open20\amos\documenti\AmosDocumenti',
	'enableCategories' => false, //changed property (default was true)
 ],
 
```

* **enableFolders** - boolean, default = false  
Define if document foldering is enabled or not. If enabled, in the lists the navigation will be hierarchical.
 
* **enableCategories** - boolean, default = true  
Define if document categories are enabled or not. If not enabled, in form/wizard the field to select document category IS NOT displayed at all.

* **enableDocumentVersioning** - boolean, default = false  
If true enable the versioning of the documents. The folders aren't versioned.
  
* **whiteListFilesExtensions** - string default = 'txt, csv, pdf, txt, doc, docx, xls, xlsx, rtf'  
List of the allowed extensions for the upload of files. Extensions string separator is ", ".

* **hidePubblicationDate** - boolean, default = false  
The documents created are always visible, hide fields publication_from, publication_to

* **layoutPublishedByWidget** - array
You can choose which elemnt tou want to show in the widget
{publisher}{publishingRules}{targetAdv}{target}{category}{status}{pubblicationdates}{pubblishedfrom}{pubblishedat}{createdat} **
```
 public $layoutPublishedByWidget = [
        'layout' => '{publisher}{targetAdv}{category}',
        'layoutAdmin' => '{publisher}{targetAdv}{category}{status}{pubblicationdates}'
    ];
```
* **showCountDocumentRecursive** - boolean, default = false  
If true show the number of document in all sub-folder, if false show the number of documente in the first level of folder


* **defaultView** - set the default view
You can setup the default view for module between 
    'expl' -> icons/explorer (new view and interface)
    'list' -> classic old view
    'grid' -> classic old table view

* **$documentsOnlyText** - boolean, default = false  
If true the main document file and the external document link are not required at all.