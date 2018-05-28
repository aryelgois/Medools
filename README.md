# Medools

> “We can solve any problem by introducing an extra level of [indirection].”

Index:

- [Intro]
- [Install]
- [Setup]
- [Using a Model]
  - [Creating a new Model]
  - [Loading from Database]
  - [Saving into Database]
  - [Deleting from Database]
  - [Accessing and manipulating data]
  - [Iterating over many rows]
  - [Foreign models]
  - [Other methods]
- [Reusing models]
- [Configuring a Model]
  - [DATABASE]
  - [TABLE]
  - [COLUMNS]
  - [PRIMARY_KEY]
  - [AUTO_INCREMENT]
  - [STAMP_COLUMNS]
  - [OPTIONAL_COLUMNS]
  - [FOREIGN_KEYS]
  - [READ_ONLY]
  - [SOFT_DELETE]
  - [SOFT_DELETE_MODE]
- [Advanced]
  - [Events]
  - [ModelManager]
- [Changelog]


# Intro

A basic framework for models related to a Database.

With this framework, you configure just the minimal for your models and then you
focus on your own code. Abstract the communication with your Database!

Creating a new model for your application is easy, just extend the [Model] base
class and define some settings.

> To interact with the Database, it is used [catfan/Medoo].


# Install

Open a terminal in your project directory and run:

`composer require aryelgois/medools`


# Setup

Before using this framework, you need a config file somewhere in your
application. It defines Servers and Databases that are accessed by the models.

The reason for the config file being `.php` is that it contains sensitive data,
and if a client could request it, nothing would be returned.

Basically, it contains data to instantiate [catfan/Medoo] objects. See details
in its [guide][new medoo]. You can also follow the [config example].

This file returns an array with the following keys:

- `servers`: Lists database Servers that can be connected and their credentials.
  Each item is an array with the following keys:
  - `server`
  - `port` _(optional)_
  - `username`
  - `password`
  - `database_type` It needs the corresponding PHP_PDO extension

- `databases`: Lists Databases referenced in the models classes. Each item is
  an array that contains:
  - `server` Server key from the previous list. If omitted, `default` is used
  - `database_name`

  A shortcut to only defining `database_name` is setting the database directly
  as string

There are other configurations that can be used, see the [guide][new medoo] for
more. You can add them where it is reasonable. When connecting to a database,
its array is merged on top of the server's. It means that a database can
overwrite a server configuration.

If the database server is not a `servers` key, it is used as it is. So the
servers array is totally optional, as long as the database contains all required
data to connect to the Database.

> The whole point of this config file is that you can define multiple databases
> in the same Server without repeating its configurations, as well define
> databases in different servers.

Also, you need to include this line in the beginning of your code:

```php
<?php

aryelgois\Medools\MedooConnection::loadConfig('path/to/config/medools.php');
```

It's a good idea to put in something like a `bootstrap.php`, which also requires
composer's autoload (prior to the line above), and is always required by your
scripts.

> [MedooConnection] works like a Medoo factory, but reuses its instances.


# Using a Model

## Creating a new Model

You can instantiate a new Model, without any parameters, to create a fresh model
object. Then add data into its columns, like in any other object. Your code
should known which columns are available, but you can call `getColumns()` for a
complete list from that model.

Your changes are stored in that object, so you need to `save()` in order to send
to the Database.


## Loading from Database

Instantiate the model with a value for its [PRIMARY_KEY], or specify an
associative array with which columns you would like to [filter][where_clause].
Only one row is loaded.

You can also `load()` after creating the object.


## Saving into Database

Use `save()` to send all changes to the Database, and `update()` to only send
some.

Some columns you might have changed can have different values afterwards, due to
some validation process or Database defaults.


## Deleting from Database

Simply use `delete()`.

If the model has [SOFT_DELETE] configured, it will just update that column. So
you are able to `undelete()` later.

Otherwise, it will completely delete the row from the Database, and `reset()`
the model object.

You can use `isDeleted()` to check if the model was deleted. It is useful when
SOFT_DELETE is configured.


## Accessing and manipulating data

Just like in any object:

- `$model->column` will return the stored data, or a foreign model \*
- `$model->column = value` will set a new data

> \* It means that you can chain the models:  
> `$model->foreign->column`

You can also get data with:

- `dump()`: Returns data from model's Table, you can [filter which rows][where_clause]
  and which columns you want
- `getPrimaryKey()`: Returns the last saved Primary Key
- `toArray()`: Returns data from the model in an array (foreigns included)

And change data with:

- `fill()`: Sets multiple columns from an array of column => value. It returns
  the object (is chainable)


## Iterating over many rows

A [ModelIterator] is provided to access multiple rows, but it provides only one
at time.

Give it a model class you want to iterate over, and some [filter][where_clause]
array. Then it will `load()` each matched row, one by one.

A shortcut is calling `getIterator()` directly from the model class, which just
asks for `$where`.


## Foreign models

This framework supports foreign models. You can configure them in the model
class, and access `$model->foreign_column`. They are simply other models,
referenced in your model.

They are loaded on demand, so you don't need to worry about loading lots of
foreigns just because you want a single column from the model.

> :warning: Warning: Be careful not to configure a circular foreign constraint.
> When serializing a model, it can fail because of recursion.


## Other methods

Useful methods that are available:

- `__isset()`: Use with [isset] to check if a column is `null`
- `__unset()`: Use with [unset] to set a column to `null`
- `__wakeup()`: You can [unserialize] a model, i.e. save in `$_SESSION` and
  recover in a another request
- `getChangedColumns()`: Lists changed columns
- `getCurrentTimestamp()`: Selects the current timestamp from Database, useful
  to keep timezone consistent
- `getData()`: Gives currently stored data
- `getDatabase()`: Gives a direct access to the Database, already connected and
  ready to use. See [catfan/Medoo] for details
- `getRequiredColumns()`: Gives a list of columns that must be set before saving
- `getStampColumns()`: Gives a list of columns that receives a timestamp
  automatically
- `isFresh()`: Tells if the object is a new Model
- `jsonSerialize()`: You can [json_encode] models! It expands foreign models
- `reload()`: Use to re fetch the row with model's Primary Key
- `undo()`: Removes changes. Pass a column name to only remove that column,
  otherwise it removes all changes

You can also add custom methods in your models, to automatically get some data
in a format, or for doing a specific task.

> There are also [event methods][events] that are automatically called by some
> base methods.


# Reusing models

To avoid creating multiple instances for the same model, there is a
[ModelManager] class which contains pointers to models already created. To
retrieve them, use the `getInstance()` method, which asks for a model class and
a [where clause][where_clause] (only one row is selected). A shortcut is calling
the `getInstance()` directly from the model, which just asks for `$where`.

See more in the [Advanced][modelmanager] section.

If you wish, you can still create a new instance for an already existing model.
The new object will not contain changes made in the old one.


# Configuring a Model

The settings are constants in each model class. You can omit some to reuse from
parent class.

Only [TABLE] and [COLUMNS] are required to define a new model.


#### DATABASE

Database key in the Medools config file

- Type: `string`
- Default: `'default'`


#### TABLE

Database's Table the model represents

> The recomended is to use a plural name for the table and its singular in the
  model name

- Type: `string`


#### COLUMNS

Columns the model expects to exist

- Type: `string[]`
- Default: `['id']`

The column may be followed by a prefered data type. If not specified, defaults
to `string`:

```php
<?php

const COLUMNS = [
    'id [Int]',
    'name',
    'data [JSON]',
];
```

Possible types are those supported by Medoo:
- `Bool`
- `Int`
- `JSON`
- `Number`
- `Object`
- `String`

These types are used when accessing the column in the database, but since Medoo
has a type auto-detection, only `Object` and `JSON` are required to
automatically unserialize/decode arrays and objects. You can still use the
others for an explicit type casting.

Note that nullable/optional columns can have a `null` value.


#### PRIMARY_KEY

Primary Key column or columns

- Type: `string[]`
- Default: `['id']`


#### AUTO_INCREMENT

Auto Increment column

- Type: `string|null`
- Default: `'id'`


#### STAMP_COLUMNS

List of columns to receive the current timestamp automatically

- Type: `string[]`
- Values: `'auto'`, `'date'`, `'time'` or `'datetime'`
- Default: `'datetime'`

The columns are automatically updated with the current timestamp on `save()` and
`update()`. This constant allows multiple timestamp columns. If the column was
manually changed, it will not be overwritten.

These columns are **implicitly** optional, because they are changed before
saving/updating the model. The exception is `auto` columns, which must be
controlled by the Database _(MySQL only allows one `timestamp`)_.

The following structure is valid:

```php
<?php

const STAMP_COLUMNS = [
    'column_a' => 'datetime',
    'column_b',
    'column_c' => 'date',
];
```

Here, `column_b` will use the default.


#### OPTIONAL_COLUMNS

List of columns which have a default value or are nullable

You don't need to include implict optional columns, like AUTO_INCREMENT,
STAMP_COLUMNS and SOFT_DELETE.

- Type: `string[]`


#### FOREIGN_KEYS

Foreign Keys map

> A map of columns in the curent model which point to a column in another model

- Type: `array[]`

Example:

```php
<?php

const FOREIGN_KEYS = [
    'local_column' => [
        'Fully\\Qualified\\ClassName',
        'foreign_column'
    ],
];
```

If the foreign class is in the same namespace, you can use `ClassName::class`.


#### READ_ONLY

If `__set()`, `save()`, `update()`, `delete()` and `undelete()` are disabled

- Type: `boolean`
- Default: `false`


#### SOFT_DELETE

If `delete()` actually removes the row or if it changes a column

> It defines the column affected by the soft delete

- Type: `string|null`
- Default: `null`

This column is **implicitly** optional, so you must define a default value in
the database schema accordingly to [SOFT_DELETE_MODE].


#### SOFT_DELETE_MODE

How the soft delete works

> Which value [SOFT_DELETE] should be setted to.

- Type: `string`
- Default: `'deleted'`

Possible value | When not deleted | When deleted
---------------|------------------|-------------
`'deleted'`    | 0                | 1
`'active'`     | 1                | 0
`'stamp'`      | null             | current timestamp


# Advanced

## Events

There are some methods that can be extended by overriding event methods. It
makes easier to extend some functionalities.

Currently, these events are available:

- `onColumnChange()`: Called when a column is changed. Useful to filter data
  before storing in the model
- `onFirstSave()`: Called on the first time a model is saved
- `onSave()`: Called every time a model is saved
- `onValidate()`: Called when data needs to be validated, before storig in the
  Database

## ModelManager

[This class][ModelManager] tracks every model loaded during a request. It aims
to avoid model duplication, mainly in foreign keys.

If you create a new instance for an already existing model, the new instance
replaces the old one in this class, but other pointers to the old instance are
not updated. To avoid this, use the `getInstance()` method provided either in
this class or in the model.


# [Changelog]


[Intro]: #intro
[Install]: #install
[Setup]: #setup
[Using a Model]: #using-a-model
[Creating a new Model]: #creating-a-new-model
[Loading from Database]: #loading-from-database
[Saving into Database]: #saving-into-database
[Deleting from Database]: #deleting-from-database
[Accessing and manipulating data]: #accessing-and-manipulating-data
[Iterating over many rows]: #iterating-over-many-rows
[Foreign models]: #foreign-models
[Other methods]: #other-methods
[Reusing models]: #reusing-models
[Configuring a Model]: #configuring-a-model
[DATABASE]: #database
[TABLE]: #table
[COLUMNS]: #columns
[PRIMARY_KEY]: #primary_key
[AUTO_INCREMENT]: #auto_increment
[STAMP_COLUMNS]: #stamp_columns
[OPTIONAL_COLUMNS]: #optional_columns
[FOREIGN_KEYS]: #foreign_keys
[READ_ONLY]: #read_only
[SOFT_DELETE]: #soft_delete
[SOFT_DELETE_MODE]: #soft_delete_mode
[Advanced]: #advanced
[Events]: #events
[ModelManager]: #modelmanager

[config example]: config/example.php
[MedooConnection]: src/MedooConnection.php
[Model]: src/Model.php
[ModelIterator]: src/ModelIterator.php
[ModelManager]: src/ModelManager.php
[changelog]: CHANGELOG.md

[catfan/Medoo]: https://github.com/catfan/Medoo

[where_clause]: https://medoo.in/api/where
[new medoo]: https://medoo.in/api/new

[isset]: http://php.net/manual/en/function.isset.php
[json_encode]: http://php.net/manual/en/function.json-encode.php
[unserialize]: http://php.net/manual/en/function.unserialize.php
[unset]: http://php.net/manual/en/function.unset.php

[indirection]: https://en.wikipedia.org/wiki/Indirection
