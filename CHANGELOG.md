# Development phase

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
