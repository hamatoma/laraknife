# Development phase

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
