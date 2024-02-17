# Development phase

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
