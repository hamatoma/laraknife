# Development phase

# 0.10.2 CSS, MediaWikiBase

- laraknife.css:
  - lkn-text-box, b, strong
- MediaWikiBase
  - fix: macro interpretation in table header

# 0.10.1 MediaWikiBase

- MediaWikiBase:
  - new paragraph type: raw text: starts with ","
  - <br /> is replaced with <br> (correct HTML5)
  - better "\n" handling in <p> paragraphs
  
# 0.9.10 MediaWikiBase

- MediaWikiBase:
  - Fix: call of stopParagraph() if line prefix has changed
  - indention: use of <dl><dd>... instead of <div class="lkn-indent">
- edit_shift.blade: "Shift" instead of "Store"

# 0.9.9 Note: task "clozeText", groups 

- button-position.blade: new: class
- const-text.blade: new: class
- nav-tabs.blade: new: class
- MediaWikiBase:
  - new: clozeMode, clozeData, errors, setClozeParameters()
- StringHelper:
  - new: explodeAssoc() implodeAssoc()
- Note:
  - new: group_id, reference_id module_id
  - new: tabs register "notes"
- Group: new: combobox()

# 0.9.8 Farbwechsel, Korrektur Import

- laraknife.css:
  - neu .lkn-font-xxx
- purple.css: Farbwechsel bei .lkn-text-infoX
- ExportController:
  - Korrektur: MAGIC-Erkennung Import

# 0.9.7 Refactoring design, import

- many improvements in CSS and components: margins, colors ...
- Export:
  - new: page import
- ExportController:
  - new: import(), importFile(), importToDatabase(), infoOfImportFile()

- PageController:
  - changed: syntax for export file
  
# 0.9.6 export of pages, module Export

- new: delete-file.blade
- new: simple.blade
- ContextLaraKnife: new. buildFileLink()
- FileHelper:
  - new: buildExportName(), decodeUrl(), encodeUrl()
  - new: fileInfoList()
  - new: class FileInfo
- StringHelper:
  - new: charSetAlphaNumeric
  - new: randomString()
- new: module Export
- refactoring of all seeders: makeing that idempotent (can be called multiple times)

# 0.9.5 Transaction

- DbHelper: new: buildSum()
- Mandator, Account, Transaction: consistent register tabs (in views)

# 0.9.4 Mandator, Account, Transaction

- new: Modules Mandator, Account, Transaction
- InitApp.templ:
  - new: PrepareDb()

# 0.9.3 Installation

- new: scripts/InstallInitApp.sh
- improved RunSeeder.sh and InitApp.templ

# 0.9.2 Mandator, Account, Transaction, Design

- laraknife.css: lkn-overview-cell: position of the icon
- new: DateTimeHelper
- new: modules Mandator, Account, Transaction

# 0.9.1 Installation, design

- col-empty.blade: fix: &bsp; for visibility
- nav-tabs.blade, standard.blade: new: 2 optional buttons
- laraknife.css, purple.css, standard.css: improvements
- ContextLaraKnife: new: isAdminOrOwner()
- User: register tabs, creation with random password
- laraknife-tool.sh:
  - new: module page
  - new: Task (copy)
  - fix: MediaWiki
  - CSS: links to standard/purple/green
  - fix: call of ./script
- CreateLaraProj.sh: new: APP_THEME
- layout.templ: improved includes
- ViewHelperLocal: new: "user-edit"
- new: TaskController

# 0.8.8 Design

- new: purple.css green.css standard.css

## 0.8.7 Design MediaWikiBase
- buttons: lkn-button
- panels: lkn-panel
- MediaWikiBase: new: @block @blockend

## 0.8.6 Design
- overview cells ()

## 0.8.5 Colors and design
- main header: h1
- layout.templ: nav bar changed (logout)

## 0.8.4 Colors and GUI

- main-header.blade+text-area.blade: not needed div removed

## 0.8.3 Module Task, EMail (Note), 

- ContextLaraKnife: new taskHelper()
- new module Task:
  - new TaskController, edit_shift.blade
- EmailHelper
  - storing array snippets instead of single values
  - new: mode note.notification
- ViewHelper:
  - new: asHtml(), buildLink()

## 0.8.2 Notes
- text-area.blade: navigation buttons with CSS class

## 0.8.1 Notes

- Note:
  - renamed: user_id -> owner_id
  - new register page: "Shift"

## 0.7.12 Module Group

- new: module Group
- file-protected.blade: attribute value renamed to filename
- ContextLaraKnife:
  - valueOf(): new parameter $preferFields
- new ViewHelper::addConditionFindInList()

## 0.7.11 MediaWiki, CSS

- MediaWiki: additional special functions, Unicode-patterns

## 0.7.10 MediaWiki tables
- MediaWiki:
  - tables: multi column definitions: "| col1 || col2"
  - tables: attributes for columns "| attributes | text"
- laraknife.css: table attributes

## 0.7.9 Pages
- new: audio-raw.blade
- audio-blade: position is empty (default)
- text-area.blade: icons/links for back up and next

## 0.7.8 MediaWiki

- laraknife.css: blue background of <ins>...</ins> items
- MediaWikiBase: 
  - jpeg recoginzed as images
  - wrong htmlentities() removed

## 0.7.7 images in MediaWiki

## 0.7.6 local CSS+JS+icons

- new bootstrap-icons.css, bootstrap.min.css, bootstrap.min.js
- nav-tabs.blade: fieldset changed to div (no nesting fieldsets)
- new: KeyValueStorage
- MediaWiki.php changed to MediaWiki.templ
- laraknife-tool.sh:
  - local CSS/JS data
  - copy of MediaWiki
- layout.templ: local CSS/JS data
- sproperties: optionsByScope(): new parameter $excludedIds


## 0.7.5 indented blocks
- MediaWiki: indent block works now

## 0.7.5 layout
- new: forms/audio-norow
- File: fix: storeFile(): module_id and reference_id are mandantory.

## 0.7.4 layout, WikiMediaBase
- panels/noform-text: no fieldset, noframe
- new: panels/text-area.blade
- MediaWiki:
  -  fix: reset of htmlBody on start
  -  fix: name of the CSS class for indents

## 0.7.3 laraknife.css WikiMediaBase

- laraknife.css: new: lrk-indent
- WikimediaBase: CSS class indent renamed to lrk-indent


## 0.7.2 WikiMedia Page laraknife-tool

- MediaWiki: extracting sub class MediaWikiBase for simple class deriving
- Page edit: fix: no loose of attributes, improvement on preview
- laraknife-tool.sh: new: sub mode "copy-from-laraknife"

## 0.7.1 Module Page

- MediaWiki: new engine. Supports more wiki elements.
- StringHelper: new textToUrl():
- new: module Page

## 0.7.0 2024.04.23 MediaWiki

- new: MediaWiki
- components:
  - combobox.blade: fix: wrong blade @else
  - file.blade: fix: wrong value attribute 
  - textarea.blade: fix: {!! !!} instead of {{ }}
- Builder:
  - Controller.templ: fix: $field settings in edit()
  - Controller.templ: $sql statement as one string
  - xxx.blade.templ: better proposals for integer/datetime... fields
- Menuitem.php, Module.php, SProperty.php:
  - new: insertIfNotExists()

## 0.6.18 2024.04.20 Fix note documents

- NoteController:
  - indexDocuments(): fix: displays only files assigned to the note

## 0.6.17 2024.04.20 Audio control, FileHelper, File, tooltips

- components:
  - new: audio.blade: audio file control
  - new: file-protected.blade a field to upload a file that cannot be changed
  - new noform-text: for articles (alice blue border)
- FileHelper:
  - new: textToFilename()
- laraknife.js:
  - initializing tooltips
- File:
  - new: storeFile()
  - new: relativeFileLink()

## 0.6.16 2024.04.16 Builder, file-exchange
- Builder
  - new import variang
- components:
  - new exchange-record.blade, exchange.blade
- FileHelper:
  - buildFileStoragePath(): $date has no default
  - new: replaceUploadedFile() storeFile()
- FileController: replacement of the file
  - new: exchange(), updateFile()

## 0.6.15 2024.04.13 Builder

- Builder: new import variant

## 0.6.14 2024.04.11 sortable-table, ContextLaraKnife

- sortable-table.blade:
  - integrated combobox (depends on not null result of $context->combobox())
- laraknife.css:
  - new: .left
- ContextLaraKnife:
  - new: combobox(), setCombobox()
- ViewHelper:
  - new: addConditionConstComparison()

## 0.6.13 2024.04.11 autologin

- UserController:
  - autologin works now

## 0.6.12 2024.04.10 autologin

- UserController:
  - autologin without hashing

## 0.6.11 2024.04.10 Builder import, autologin

- Builder
  - improved import
- ContextLaraKnife:
  - new: $role, hasRole(), isAdmin()
- UserController
  - correction of "autologin"
- SProperty
  - new idOfLocalization()

## 0.6.10 2024.03.30 InitApp.sh Own.sh

- README.md: installation tips
- *modify_user_role.php: endautologin->nullable()
- InitApp.templ improvments
- Own.sh: no sqlite database


## 0.6.9 2024.03.30 Autologin

- checkbox.blade: corrections for mode "belowLabel"
- new:const-text.blade
- login.blade: new checkbox "Remain signed in"
- User:
  - new attributes: autologin, endautologin, rights, options
  - storing a "auto login password" in a cookie
- new: InitApp.templ: a script for cloning of the project

## 0.6.8 2024.03.26 ContextLaraKnife, DbHelper, ViewHelper

- ContextLaraKnife:
  - valueOf(): using of $this->fields if $this->model has no matching attribute
- DbHelper:
  - addOrderBy(): handling of x.y items: changed to x.`y`
- ViewHelper:
  - new: adaptCheckbox()

## 0.6.7 2024.03.24 logo, localization

- ContextLaraKnife: new: model2 and model3
- StringHelper: new singularOf()
- new: laraknife_logo_64.ico and *.png
- new: resources/lang/de_DE/*.php
- User: new attribute localization
- laraknife-tool.sh:
  - new: LANG_DEFAULT. 
  - new directory layout of lang instead of resources/lang (Laravel 11)
  - copy of favicon and logo image
  - fix: RunSeeders.sh instead of RunSeeder.sh
- Own.sh: better description
- CreateLaraProj.sh: .env corrections
- IncludeLara.sh: swap of MODE and BASE
- layout.templ: correction of logo
- RoleSeeder: fix of prio for User
- SPropertySeeder: scope "localization"
- MenuItemController:
  - create(): completion of empty fields: label and link

## 0.6.6 2024.03.09 correction for module term

- laraknife.sh:
  - web.php: insertion of module Term
  - BuildLink: insertion of module Term

## 0.6.5 2024.03.09 Builder, checkbox, bool columns

- checkbox.blade.templ: fix: alignment
- Builder.php:
  - new adaptRouting()
  - recognition of column type "boolean"
- controller.templ: fix: parameters of comboboxDataOfTable() on foreign keys
- create.blade.index ... show.blade.index: handling of bool types
- TermController: fix: filter field "text"


## 0.6.4 2024.03.09 module term, Builder
- new: checkbox.blade.php
- string.blade.php: attribute "type"
- Builder.php:
  - reference changed to refId and refTable
  - bigtext/text changed to text/string
  - handling of date / datetime / timestamp fields
- ContextLaraKnife.php
  - new: asDateTimeString()
- ViewHelper.php:
  - new: addConditionDateTimeRange()
  - new: adaptFieldValues()
- new module Term
- laraknife-tool.sh: content of $PROJ.css
- controller.templ: 
  - fix: parameters of comboboxDataOfTable()
  - default value of user_ix field: auth()->id()

## 0.6.3 2024.03.06 installation process
- <app>.css: color tab header

## 0.6.2 2024.03.06 installation process
- laraknife-tool.sh: syntax error

## 0.6.1 2024.03.06 installation process
- laraknife-tool.sh:
  - handling of ModuleController
  - creating app/Mail
  - calling ModuleSeeder, NoteSeeder
  - new: SetupNginx
- layout.templ: logo
- ViewHelperLocal: using module and reference

## 0.6.0 2024.02.29 notes with documents, visibility
- improvements HTML components
- FileHelper::buildFileLink(): fix in link
- Notes: new field visibility_scope
- Notes: embedded file upload facility
- Files: new field visibility_scope
- new module Module

## 0.5.8 2024.02.25 password forgotten
- UserController: new: answer(), forgotton()
- login.blade.php: link to "password forgotten"
- new: ForgottenPassword (mail), EmailHelper.php

- ContextLaraKnife: new: getSnippet(), setSnippet()
- note: edit.blade.php: x-laraknife.panels.standard instead of x-laraknife.panels.edit
- new: forms/link.blade.php
- new: panels/noform.blade.php+standard.blade.php

## 0.5.7 2024.02.19 laraknife-tool.sh, ViewHelperLocal
- laraknife-tool.sh
  - link uploads -> upload
  - web.php: missing ";"
  - wrong copying of ViewHelperLocal
- ViewHelperLocal: wrong class name

## 0.5.6 2024.02.18 laraknife-tool.sh: ViewHelperLocal

## 0.5.5 2024.02.18 laraknife-tool.sh: links on Helpers, css, js

## 0.5.4 2024.02.18 laraknife-tool.sh: was blocked by Test

## 0.5.3 2024.02.18 laraknife-tool.sh, seeding, layout
- laraknife-tool.sh:
  - additional module: file
  - link to public/upload
  - fix in BuildLinks: helpers
  - seeding FileSeeder
- layout.templ: logout button rightside, "settings" -> userame

## 0.5.2 2024.02.18 handling of filename in FileController::create()
- new: FileHelper::extensionOf()
- FileController: addition of the source extension if needed

## 0.5.1 2024.02.18 module "file", controller.templ, show.blade.templ
- new module file
- new ContextLaraKnife::callback()
- new FileHelper
- menuitem/create+edit.blade.php: the link to the bootstrap icon page
- note/edit: missing buttons added
- controller.templ: index(): wrong parameters for addConditionPattern()
- show.blade.templ: wrong "row" for string fields

## 0.5.0 2024.02.17 navigation tabs, login
- new: x-laraknife.layout.cell,  x-laraknife.layout.nav-tabs
- new: NavigationTabs
- corrections: StringHelper::createPassword()
- new: NoteController::editDocuments() note/edit_documents.php
- login: no more default behaviour, we use our own encryption

## 0.4.6 2024.02.15 corrections for web.php
- laraknife-tool.sh: no more: Auth::routes()

## 0.4.5 2024.02.15 corrections
- laraknife-tool.sh: creating resources/views/layouts
- CreateLaraProj.sh: "php artisan ui bootstrap" without "-auth"
- UserSeeder.php: wrong ','

## 0.4.5 2024.02.15 corrections
- menuitem/index.blade.php: wrong column
- user/create.blade.php: missing role_id
- laraknife-tool.sh: call of seeders
- layout.templ: menu_main instead of menu_start
- UserController: errors in store() and update()

## 0.4.4 2024.02.15 other password encryption
- new: StringHelper::createPassword()
- laraknife-tool.sh:
  - AdaptModules(): corrections for role_id
  - AdaptModules(): correction in web.php
  - layout.blade.php: correction: user-edit-current
  - UserSeeder: creation of a random password for the admin,  writing into .lrv.credentials
  - UserController: many corrections. Now using another password encryption

## 0.4.3 2024.02.13 database seeders, refactoring
- database is filled by seeders now
- renamed: forms/text.blade.php -> forms/string.php
- renamed: forms/bigtext.blade.php -> forms/text.php
- Builder: corrected path to test data
- numberOfButtons() moved from DbHelper to ViewHelper
- new module: note

## 0.4.2 2024.02.13 module menu, renaming of the components
- sub directories: buttons, forms, panels
- module menu renamed to menuitem
- UserController: new login(), logout(), editCurrentUser()
- backend.blade.php: logout is now a link (not a button)
- XxxController::routes(): using middleware('auth')
- Auth::routes() removed

## 0.4.1 2024.02.11 main menu, bootstrap icons
- usage of bootstrap icons
- new module: menu

## 0.4.0 2024.02.09 module menu, renaming of the components
- sub directories: buttons, forms, panels
- new module: menu

## 0.3.1 2024.02.01 module role, build links

- module role: adapted to new module handling
- laraknife-tool.sh: building link to lang/de_DE/validation.php

## 0.3.0 2024.02.01 Refactoring module handling, field specific errors

- new: class ContextLaraKnife
- components:
  - all input fields get a error handling:  <x-laraknife.field-error>
  - the id of input fields is now fld_&lt;name>
  - x-form-error: removed: attribute "error"
  - x-sort-table-panel: using $context instead of $fields
- views:
  - all views uses $context now instead of $fields
  - create.blade.php uses the form action of &lt;module>-store
  - edit.blade.php uses the form action of &lt;module>-update
  - usage of $context->valueOf() for receiving the current value
- calling of view receives the parameter context
- DbHelper::comboboxDataOfTable(): $select is nullable now
- controller.templ:
  - using $context
  - create(): no more call of validate(): this is done in store()
  - edit(): no more call of validate(): this is done in update()
  - store() and update(): using back()->withErrors()->withInput() for error recovering
  - routes(): defining put(..store...)


## 0.2.6 2024.01.28 Refactoring controller
- refactoring controller.templ, RoleController, UserController, SPropertyController
  - detection of the current button
  - $_POST replaced by $request->all()
  - index(): $records will be fetched from $pagination

## 0.2.5 2024.01.28 Pagination links work, password change
- new: setposition.blade.php
- new: changepw.blade.php
- edit.blade.php: new button "set password"
- sortable-table-panel.blade.php: wrong value in field pageIndex
- Pagination.php: records will be fetched inside. Limit and offset are used now
- laraknife.js: new: paginationClick() and setPaginationClick()
- RoleController, SPropertyController, UserController: index(): records from $pagination

## 0.2.4 2024.01.27 Example module "notes" works better
- laraknife-tool.sh: new mode "rest"
- controller.templ: corrections in index() and show()
- index.templ: class "lkn-autoupdate" for sproperties comboboxes

## 0.2.3 2024.01.27 Example module "notes" works
- edit-panel.blade: added: error
- Builder: new condition isSecondary in ##CASE## construct
- laraknife-tool.sh: created "Build": parameter "prod" for production
- *.templ: many errors corrected

## 0.2.2 2024.01.25 Wrong finding autoload.php in Builder.php
- additional path for autoload.php in Builder.php

## 0.2.1 2024.01.25 Refactoring of installation
- laraknife-tool.sh: new tasks: init-i18n fill-db adapt-modules create-layout create-home
- new: CreateLaraProj.sh IncludeLara.sh PutBug.sh
- new: templates/home.templ + layout.templ

## 0.2.0 2024.01.11 Template model switched to "CASE(fields)"
- Builder.php: refactoring: pattern based conditions for field based templates
- Builder.php: new modes: test:mini + test:maxi

## 0.1.0 2023.12.31 tutorial taskx works
- corrections in controller.templ

## 0.0.6 2023.12.30 all parts tested with taskx
- corrections in bigtext.blade.php, combobox.blade.php
- Builder.php: wrong argument count test
- corrections in DbHelper.php
- corrections in index.blade.php 
- corrections in controller.templ create.blade.templ edit.blade.templ show.blade.templ
- corrections in laraknife-tool.sh
- SPropertyController: no button check in destroy()
- corrections in UserController

## 0.0.5 2023.12.28
- ViewHelper::buildEntriesOfCombobox() refactoring: using array of items instead of texts + values
- sproperty/index.blade.php: removing duplicate button

## 0.0.4 2023.12.27
- Builder.php works in the project directory
- Refactoring components / templates
- User module works

## 0.0.3 2023.12.24
- knowledge: components with $slot, components with PHP expressions (argument :param)
- CSS pagination: No gap between status text and table
- Legend: now as bordered text over the top line
- new: filter-panel.blade.php
- new sortable-table-panel.blade.php
- new: DbHelper::addConditions()
- laraknife.js: getElementById() can return null

## 0.0.2 2023.12.21
- new: Pagination
- pagination works in sproperties

## 0.0.1 2023.12.20
- CSS classes now start with lkn-
- new: hidden-button.blade.php sortable-table.blade.php
- helper classes moved to resources/helpers (and namespace App\Helpers)
- new: laraknife.js
- SPropertyController::index(): table header sort works
