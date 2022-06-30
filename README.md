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

* **enableContentDuplication** - boolean, default = false  
If true enable the content duplication on each row in table view.

* **enableCatImgInDocView** - boolean, default = false  
If true replace the document icon with the category image in the document view and lists.


# Install drive
Insert the following configuration in the module
```
 'documenti' => [
    'enableGoogleDrive' => true,
    'googleDriveConf' => [
         'developerKey' => '<developer_key>',
         'clientId' => "<client_id>",
         'clientSecret' => "<client_secret>",
         'appId' => "<app_id>",
         'relativeRedirectUrl' => '/documenti/documenti/own-interest-documents',
         'relativePathCredential' => '/web/credenziali.json',
 
         'emailServiceAccount' => 'account@example_name.iam.gserviceaccount.com',
         'relativePathCredentialServiceAccount' => '/web/credenziali_service_account.json'
     ]
  ];
```


### Obtain google drive the credential
 1. Go to google drive developer console <https://console.developers.google.com/apis/api/drive>
 2. Create a project
 3. Click on "Credenziali" on the left and next 
 4. Click on "create credential" to generate:
    - Chiave API  (developerKey)
    - ID client OAuth 2.0  (cliendId)
    - Chiavi accound di servizio
   
### Enable Google Drive API and Google Picker API
 1. Go to google drive developer console <https://console.developers.google.com/apis/api/library> 
 2. Select the project
 3. Search Google Drive API/ Google Picker API and enable both
   
 #### Configure Service Account
 5. After generate the service account and his key you can find the:
    - Service account email
 6. Generate the JSON KEY for the service account that you will upload to your server (relativePathCredentialServiceAccount)
 #### Configure client
 7. After generate the ID client OAuth 2.0
 8. Compile the Origin javascript autorizzate URI (site url)
 9. Compile the URI DI REINDIRIZZAMENTO
    - https://developers.google.com/oauthplayground
    - http://poi.devel.open20.it/documenti/documenti/own-interest-documents
 10. You can find the clientId and the clientSecret
 11. Generate the JSON to upload on the server, and configure its path on 'relativePathCredential' 
 ### App id
 You can find the app id on the main page (è evidenzioata in verde)
 
 ###Verify app
 if you click on the left on "Schermata consenso OAuth" anc next on "modifica" you can configure it for
  ***development*** or ***verify the app***
  
  ###Example images of configuration 
  You can find them in the ***/docs/*** folder
  
  