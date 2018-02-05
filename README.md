> “We can solve any problem by introducing an extra level of [indirection].”

# Intro

## (pt_BR)

Um framework básico para modelos relacionados a um Banco de Dados.

Com esse framework, você configura o mínimo para os seus modelos e foca no seu
próprio código. Abstraia a comunicação com seu Banco de Dados!

Criar um novo modelo para sua aplicação é simples, basta estender a classe base
[Model] e definir algumas opções.

> Para interagir com o Banco de Dados, é usado o [catfan/Medoo].


## (en_US)

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
application. This file setups some data for [catfan/Medoo]. Use this
[example][config_example].

Also, you need to include this line in the beginning of your code:

```php
<?php

aryelgois\Medools\MedooConnection::loadConfig('path/to/config/medools.php');
```

It's a good idea to put in a bootstrap which also requires composer's autoload
(prior to the line above), and is always required by your scripts.

The method called is from [MedooConnection], which works as a factory to reuse
the Database connections. The reason for the config file being `.php` is that it
contains passwords, and if this file is accessible in the public directory of
your app, loading it will show nothing.


# Using a Model

## Creating a new Model

You can instantiate a new Model, without any parameters, to create a fresh model
object. Then add data into its columns, like in any other object. Your code
should known which columns are available, but you can look at `$model::COLUMNS`
for a complete list from that model.

Your changes are stored in that object, so you need to `save()` in order to send
to the Database.


## Loading from Database

Instantiate the model with a value for its `PRIMARY_KEY`, or specify an
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

If the model has `SOFT_DELETE` configured, it will just update that column. So
you are able to `undelete()` later.

Otherwise, it will completely delete the row from the Database, and `reset()`
the model object.


## Accessing and manipulating data

Just like in any object:

- `$model->column` will return the stored data, or a foreign model *
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


## Reloading the model

Use `reload()` to re fetch the row with model's Primary Key.


## Iterating over many rows

A [ModelIterator] is provided to access multiple rows, but it provides only one
at time.

Give it a model instance you want to iterate over, it can be a fresh one, and
some [filter][where_clause] array. Then it will `load()` each matched row, one
by one.


## Foreign models

This framework supports foreign models. You can configure them in the model
class, and access `$model->foreign_column`. They are simply other models,
referenced in your model.

They are loaded on demand, so you don't need to worry about loading lots of
foreigns just because you want a single column from the model.

> :warning: Warning: Be careful not to configure a circular foreign constrain.
> When serializing a model, it can fail because of recursion.


# Reusing models

To avoid creating multiple instances for the same model, there is a
[ModelManager] class which contains pointers to models already created. To
retrieve them, use the `getInstance()` method, which asks for a model class and
a [where clause][where_clause] (only one row is selected). A shortcut is calling
the `getInstance()` directly from the model, which just asks for `$where`.

See more in the [Advanced](#modelmanager) section.

If you wish, you can still create a new instance for an already existing model.
The new object will not contain changes made in the old one.


# Configuring a Model

The settings are constants in each model class. You can omit some to reuse from
parent class.

Only TABLE and COLUMNS are required to define a new model.


#### DATABASE

Database name key in the Medools config file

- Type: `string`
- Default: `'default'`


#### TABLE

Database's Table the model represents

> The recomended is to use a plural name for the table and it's singular in the
  model name

- Type: `string`


#### COLUMNS

Columns the model expects to exist

- Type: `string[]`
- Default: `['id']`


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

- **NOTE**: Columns with timestamp controlled by the Database must be listed
  with `'auto'`

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

List of optional columns

> List all columns which have a default value (e.g. timestamp) or are nullable.
  AUTO_INCREMENT is always optional and does not need to be here.

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


#### READ_ONLY

If `__set()`, `save()`, `update()`, `delete()` and `undelete()` are disabled

- Type: `boolean`
- Default: `false`


#### SOFT_DELETE

If `delete()` actually removes the row or if it changes a column

> It defines the column affected by the soft delete

This column is **implicitly** optional, so you must define a default value in
the database accordingly to SOFT_DELETE_MODE. *(see below)*

- Type: `string|null`
- Default: `null`


#### SOFT_DELETE_MODE

How the soft delete works.

> Which value SOFT_DELETE should be setted to.

- Type: `string`
- Default: `'deleted'`

Possible value | When not deleted | When deleted
---------------|------------------|-------------
`'deleted'`    | 0                | 1
`'active'`     | 1                | 0
`'stamp'`      | null             | current timestamp


## Advanced

Use `getDatabase()` for a direct access to the Database, already connected and
ready to use. See [catfan/Medoo] for details.

You can add custom methods to your models, to automatically get some data and
format as needed.

#### Hooks

There is a Hook concept in this framework, where you can add specific methods
which are automatically called by default methods. It makes easier to extend
some functionalities.

Currently, these hooks are available:

- `validateHook()`: Use it to validate the data before sending to the Database.
  Make sure your code can validate some columns or all of them, depending on the
  `$full` argument.

#### ModelManager

[This class][ModelManager] tracks every model loaded during a request. It aims
to avoid model duplication, mainly in foreign keys.

If you create a new instance for an already existing model, the new instance
replaces the old one in this class, but other pointers to the old instance are
not updated. To avoid this, use the `getInstance()` method provided either in
this class or in the model.


[config_example]: config/example.php
[MedooConnection]: src/MedooConnection.php
[Model]: src/Model.php
[ModelIterator]: src/ModelIterator.php
[ModelManager]: src/ModelManager.php

[catfan/Medoo]: https://github.com/catfan/Medoo

[where_clause]: https://medoo.in/api/where

[indirection]: https://en.wikipedia.org/wiki/Indirection
