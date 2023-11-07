# laraknife
Helpers for development with Laravel

## Installation

```
composer require hamatoma/laraknife
```

## Glossar
- Module: the composition of a database table, a controller and a view

## Description
The package contains:
- Blade templates for quick building Blade forms. 
    - See src/resources/views/laraknife/form
    - See doc/examples/edit.blade.php
- Modules:
    - SProperty: define properties organized in groups ("scope").

## Module SProperty
Define properties organized in groups ("scope").
There is a defined order of the property of a given group.

Example: 
- scope: "state"
- members: "active", "inactive", "locked", "deleted"
There are 4 members in SProperty with the names "active" ... "deleted". Each has the scope "state".
The attributes are:
- scope: defines the group the property belongs to
- name 
- shortname
- order: a number defining the order inside the group.
- value
- info

 
