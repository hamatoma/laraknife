# Development phase

# V0.11.36 radiogroup ViewHelper

- new: radiogroup.blade
- ViewHelper::selectByCombobox(): the first entry of the radiogroup is selected automatically

# V0.11.35 ViewHelper

- ViewHelper::selectByCombobox(): handling of "back" button

# V0.11.34 panels/edit.blade

- panels/edit.blade: additional parameter "with_storage"

# V0.11.33 File

- File::fileData(): $fileId may be null

# V0.11.32 Docu

- modify a table

# V0.11.31 UserController, File

- UserController: date changed from y-m-d to Y-m-d
- File: new: fileData()

# V0.11.30 show.blade in transaction

- show.blade in transaction: wrong x-laraknife.string => x-laraknife.forms.string

# V0.11.29 ViewHelper file/index.blade

- ViewHelper:
  - corrections in changeTitleOrId()
  
# V0.11.28 ViewHelper file/index.blade

- ViewHelper:
  - corrections in addFieldIfMissing()
  - new: changeTitleOrId()
- file/index.blade: additional column "id"

# 0.11.27 checkbox DbHelper ViewHelper

- checkbox.blade.php: wrong spaces
- DbHelper::comboboxDataOfTable(): $undefinedText == '--' (<none>)
- new: ViewHelper::addTitleOrId()

# 0.11.26 title-or-id.blade, ContextLaraKnife

- new: title-or-id.blade.php: a field showing a title and that can be filled with an primary key
- new: ContextLaraKnife::placeholderOf()


# 0.11.25 MediaWikiBase File Note

- MediaWikiBase: 
  - prefix of internal link may be "upload:" or "file:"
- File + Note:
  - edit: the relative path of the file will be shown too
- File:
  - if title is empty the title will be built from filename

# 0.11.24 Title of note documents is optional

- if not title is given the filename is taken in a modified kind
- NoteController:
  - storeDocument(): if title is empty it will be constructed from the filename
  - updateDocument(): if title is empty it will be constructed from the filename
- create_document: title moved behind filename
- development.md: improvement
- UserController: forgotten(): email is only sent if email exists in table users
- File: new: filenameToText()

# 0.11.23 forgotten-answer.blade
- forgotten-answer.blade: Syntax error

# 0.11.22 link.blade
- link.blade: no <a> construct on empty references

# 0.11.21 ContextLaraKnife FileHelper
- ContextLaraKnife: new: pathUpload() urlUpload()
- FileHelper: new pathUpload() urlUpload()

# 0.11.20 ContextLaraKnife
- ContextLaraKnife: +urlStorage()
- controller.templ: "<Please select>" replaced in DbHelper::comboboxDataOfTable()
- most controllers: 
  - "<Please select>" replaced in DbHelper::comboboxDataOfTable()
  - "__('all')" replaced in DbHelper::comboboxDataOfTable()

# 0.11.19 FileHelper
- FileHelper:
  - new: baseDirectory() documentRoot()

# 0.11.18
- MediaWikiBase: writeInternalLink(): null check

# 0.11.17 PageController laraknife-tool.sh layout.templ
- new: file_into_file.py and User.insert.txt
- laraknife-tool.sh:
  - AdaptModules(): usage of file_into_file.py
- PageController:
  - showPretty() renamed to showById()
  - showById() works in auth and guest mode
- layout.templ
  - non auth(): no "administration" menu item, "login" button field


# 0.11.16 UserController ForgottenPassword PageSeeder layout
- ForgottenPassword: missing ForgottenPassword->snippets
- layout.templ: public menu if not @auth
- PageSeeder: +wiki-public +info-public
- UserController: index(): redirect if not isAdmin()

# 0.11.15 MediaWikiBase StringHelper Page PageController
- MediaWikiBase:
  - writeInternalLink(): new syntax: [[page:xxx|title]] and [[page title|linktext]] 
  - writeInternalLink(): $text may be null
  - writeText(): pattern of [[link]]: link text is optional
- StringHelper:
  - textToUrl(): replacement of 'ä' to 'ae' ...
- Page:
  - new: byId(), byNameAndType(),byTitle() 
  - create.blade: "columns" removed
- PageController:
  - create(): automatic creation: there may be a field "title2"
  - new: linkOfPageCreation()
  - routes(): recognition of /page-showbyid/{page}

# 0.11.14 HourController
- HourController:
  - index() + multiple(): removing "else" from (count($fields) == 0)
- DateTimeHelper:
  - new: dBDateTimeToDateTime() and dateTimeToString()

# 0.11.13 Hours
- new *_modify_hours_interested.php

# 0.11.12 Hours
- development.md: modify example
- Hour: new attribute "interested" 
- Hour: new page "multiple"
- HourController: new multiple(), handling of "interested"

# 0.11.11 Person (Addresses), combobox setting on error

- Person:
  - "Addresses": const text and links to edit the location/address
- AccountController, MandatorController: 
  - fix: $fields for addConditionPattern()
- AddressController, FileController...
  - Wrong reset of comboboxes on errors:
  - create(): fix: $fields = { ... => old(...)...}

# 0.11.10 PersonController

- findAddresses(): handling of empty info

# 0.11.9 NoteController PersonContoller

- NoteController: store():  - redirect to /note-edit/$id
- Person: 
  - PersonController::store(): redirecto to /person-address/$id
  - address.blade: added: lastname and firstname (readonly)

# 0.11.8 ViewHelper NoteController

- ViewHelper::addConditionPattern(): fix: wrong usage of :field instead of "?"
- NoteController::index(): wrong usage of addConditionPattern() [2 times]


# 0.11.7 DbHelper, ViewHelper, Hour: +factor

- DbHelper::buildSum(): neuer Parameter $fieldFactor
- ViewHelper::addConditionPattern(): fix: overwriting $fields
- Hour: new attribute "factor"

# 0.11.6 

- ViewHelper::addConditionPattern(): new parameter $fields
- ViewHelper::addConditionDateTimeRange(): new parameter $fields
- UserController: index(): default value of "role" is empty
- controller: 
  - create(): "," instead of "#comma#"
  - index(): no "else" for count($fields) == 0
  
# 0.11.5 Sort order term index

- Sort order term index: now ascending

# 0.11.4 ContextLaraKnife Term Change

- ContextLaraKnife:
  - new: $dayOfWeek
  - asDateString()+asDateTimeString(): new parameter $positionDayOfWeek
  - new: asDayOfWeek()
- Term:
  - index.blade: format of the date time, new column: "Day"
  - TermController::index(): default "term": the current date
  - TermController::index(): limited description length (80) 
- Change:
  - index.blade: format of the date time


# 0.11.3 Hour ViewHelper DateTimeHelper

- new module Hour
- fix ViewHelper::addConditionComparison(): wrong usage of "parameters"
- DbHelper::buildSum(): handling of format "h:m" (time)
- new: DateTimeHelper::timeToMinutes()
- new: ContextLaraKnife::asDateString()

# 0.11.2 laraknife-tools.sh controller.templ ExportSeeder

- development.md: link to icons
- laraknife-tools.sh:
  - AdaptModules(): added modules: Change Person Address Location Mandator Account Transaction
  - FillDb(): Seeders from Change Person Address Location Mandator Transaction
- controller.templ: show(): wrong "'"
- ExportSeeder: new entry for "import"
- MenuitemSeeder: new entry for "startpage"
- PageSeeder: new entry ('pagetype', 'wiki-encrypted')

# 0.11.1 *create_persons* renamed

- migrations/*create_persons*.php renamed (to change the creation order)

# 0.10.19 Account, Mandator, Transaction

- fix wrong module name in Change::createFromModel() and Change::createFromField()

# 0.10.18 docu Account, Mandator, Transaction ChangeSeeder

- new: docu/development.md
- ChangeSeeder/Change: new values of "changetype" items (conflicts)
- Account, Mandator, Transaction: logging with Change
- PersonController::update(): redirect to "edit"

# 0.10.17 StringHelper File Note Person

- StringHelper::textToUrl(): $text may be null
- Note:
  - index(): "title" -> "document"
  - updateDocument(): creation of a Change instance
- File: creation of a Change instance on updates/inserts
- Page: fix store(): $UPDATE -> $CREATE
- Person: storeAddresses(): creation of a Change instance

# 0.10.16 Change Note Term Address Location Builder

- new module Change
- purple.css: new lkn-text-info5
- Builder: CaseInfo.__init__(): index checking
- Builder: main(): file checking
- ViewHelper: new: addConditionComparison()
- Note: index.blade: error while sorting
- Note: index(): new column file count
- forgotten.blade: hiding buttons "store" and "cancel"
- replacement addConditionComparism() -> addConditionComparison()
- Location: edit(): "users" -> "persons"
- Address: edit.blade: new: "priority"
- Page, Note, Term, Address, Location: logging of insert, update and delete: Change::createFromFields()

# 0.10.15 

- PersonController:
  - index(): Fix: "t0.info" instead of "info"
  
# 0.10.14 help in addressses for a person

- address.blade: a help link
- ViewHelperLocale.templ: corrections in notes-edit and person-edit

# 0.10.13 +Person Location Address OPEN!!!

- new: module Person
- Address+Location: owner_id -> person_id
- laraknife-tool.sh: help message


# 0.10.12 ViewHelper Note Term Export

- ViewHelper: new: addConditionVisible(), addConditionRawSql()
- Exports: index.blade: removed: column "edit"
- Note:
  - edit.blade: layout changed
  - index.blade: layout changed
  - NoteController: index(): visibility filter
  - NoteController: rules(): handling of "body"
- Term: 
  - create.blade+edit.blade: label "Duration" changed
  - TermController: index(): visibility filter
- Export: ExportSeeder: menu label: "Exports" -> "Imports"

# 0.10.11 Pages, UserController, laraknife-tool, ContextLaraKnife

- new: wiki-text.blade.php
- UserController: 
  - Fix: handling of empty $fields
  - Fix: getNavigationTabInfo(): wrong index
  - Fix: rules(): roleId on ! $isCreate
- PageController
  - edit(): pagetype_scope changeable
  - new: editWiki.blade + showwiki.blade
  - showPretty(): using editwiki.blade for pagetype = 'wiki'
  - new showStartPage(), showUserPage()
- laraknife-tool.sh:
  - Help messages improved
  - Fix: LinkModule(): wrong symbolic link
- ContextLaraKnife:
  - fix: id() -> id
  - new: readonlyUnlessOwner()


# 0.10.10 Builder, Address, Location

- Builder
  - new: FieldInfo.nameShort (without '_id' or '_scope')
  - replaceVariables(): macro '#fieldShort#'
- new modules Address and Location
- view/file.index.php: 'filegroup_scope' -> 'filegroup'
- FileController.index(): 'filegroup_scope' -> 'filegroup'
- view/note.index.php: 'category_scope' -> 'category', 'notestatus_scope' -> ...
- NoteController.index(): 'category_scope' -> 'category' ...
- view/term.index.php: 'visibility_scope' -> 'visibility'
- TermController.index(): 'visibility_scope' -> 'visibility'
- TermController.index(): table 'users' for 'owner_id'
- controller.templ:
  - function create(): #field -> #field#
  - function index(): handling of nameLike(_(owner_id|user_id)$)
  - function index(): DbHelper::comboboxDataOfTable(): '<Please select>' -> 'all' 
  - function create(): + handling of 'owner_id'
- index.template: using of '#fieldShort# '

# 0.10.9 MediaWikiBase, laraknife.css

- laraknife.css: new:  .lkn-empty-line
- MediaWikiBase: new: <nl> (newline)

# 0.10.8 Builder MediaWikiBase Export seeders

- new wiki_help.txt
- Builder.php: new in main(): "import-table"
- MediaWikiBase: fix: "=" (header): no crash if no ending "="
- new: TranslatorHelper.php SimpleTranslatorHelper.php StandardTranslatorHelper
- corrections in laraknife-tool.sh
- new: ExportSeeder.php
- new: Export.php
- ExportController: new importFile()
- MenuItem.php: new: buildMinimalMenu()

# 0.10.7 TaskHelper Pages

- new: TaskHelper.php
- Pages
  - views: adaption in edit, index. new: show-colX.php
  - PageController: Adjustments due to table changes

# 0.10.6 CSS MediaWikiBase Pages

- laraknife.css: attributes of .table and .lkn-text-box
- MediaWikiBase: check of the uniqueness of fieldnames and correction if needed
  - new: checkFieldnames() buildNextFieldname()
  - toHtml(): the input string now can be changed
- Pages: 
  - removed: column "columns"
  - renamed: cacheof_id -> reference_id
  - new: previous_id, next_id, up_id

# 0.10.5 Files

- FileController
  - index(): default value of "user_id" is "<all>"

# 0.10.4 Files, CSS

- edit.blade + index.blade: updated_at instread of created_at
- laraknife.css: ".lkn-table-no-body-grid th" like "... td"
  

# 0.10.3 MediaWikiBase

- writeHeader(): calling writeText() for interpreting the text.

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
