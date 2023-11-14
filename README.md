# laraknife
Helpers for development with Laravel

## Installation

```
composer require hamatoma/laraknife
vendor/hamatoma/laraknife/scripts/laraknife-tool.sh build-links
# If that is an update: use
vendor/hamatoma/laraknife/scripts/laraknife-tool.sh build-links --force
```

## Glossar
- Module: the composition of a database table, a controller and a view

## Description
The package contains:
- Blade components and views for quick building Blade views and forms. 
    - See resources/views/laraknife
    - See resources/components/laraknife
- Modules:
    - SProperty: define properties organized in groups ("scope").

## Module SProperty
Define properties organized in groups ("scope").
There is a defined order of the property of a given group.

Example: 
- scope: "status"
- members: "active", "inactive", "locked", "deleted"

There are 4 records in SProperty with the names "active" ... "deleted". Each has the scope "state".

The attributes are:
- scope: defines the group the property belongs to
- name 
- shortname
- order: a number defining the order inside the group.
- value
- info

There are helper functions for building comboboxes from that data: see SProperty.php and combobox.blade.php
 
