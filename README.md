# Intro

## (pt_BR)

Um básico framework para modelos relacionados a um Banco de Dados.

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

`composer require aryelgois/medools:dev-master`


# Using a Model

## Creating a new Entry

You can instance a new Model, without any parameters, to create a fresh model
object. Then use `set()` to add data into the model. Your code should known
which columns are available, but you can look at `Model::COLUMNS` for a complete
list from that model.

Your changes are stored only in that object, so you need to `save()` in order
to send to the Database.


## Loading an Entry from Database

Instance the model with a value for its `PRIMARY_KEY`, or specify an associative
array with which columns you would like to [filter][where_clause]. Only one row
is loaded.

Then you can `get()` any stored column or `set()` new data (remember to
`save()` or `update()` these data).


## Saving an Entry

Use `save()` to send all changes to the Database, and `update()` to only send
some.

Some columns you might have changed can have different values afterwards, due to
some validation process or Database defaults.


## Deleting an Entry

Simply use `delete()`. If the model has `SOFT_DELETE` configured, it will only
update that column. Otherwise, it will completely delete the row from the
Database, and `reset()` the model object.


## Accessing data

A few methods are provided by default. They are:

- `get()`: Gives one column
- `getData()`: Gives all the stored data in the model. You can pass `true` to
  get from foreign models as well.
- `getForeign()`: Returns another model for the foreign constrain in a column
- `getPrimaryKey():` Returns the last saved Primary Key.

#### Dumping data from Table

Every model can `dump()` its own Table, optionally a [part of it][where_clause].


## Reloading the model

Use `reload()` to re fetch the row with models Primary Key. Foreigns are also
reloaded.


## Iterating on many entries

A [ModelIterator] is provided to access multiple rows, but it provides only one
entry at time.

Give it a model instance you want to iterate over, it can be a fresh one, and
some [filter][where_clause] array. Then it will `load()` each matched row, one
by one.


## Foreign models

This framework supports foreign models, so you can configure them in the model
class, and access with `getForeign()`. They are simply other models, referenced
in your model.

You can add custom methods to your models, so they automatically `get()` some
data from it's foreigns, as needed.

> Warning: Be careful not to configure a circular foreign constrain. PHP might
> hang trying to create infinite models.


## Hooks

There is a Hook concept in this framework, where you can add specific methods
which are automatically called by default methods. It makes easier to extend
some functionalities.

Current, these hooks are available:

- `validateHook()`: Use it to validate the data before sending to the Database.
  Make sure your code can validate some columns or all of them, depending on the
  `$full` argument.


## Advanced

Use `getDatabase()` for a direct access to the Database, already connected and
ready to use. See [catfan/Medoo] for details.


# Configuring a Model

The settings are constants in each model class. You can omit some to reuse from
parent class.

Only TABLE and COLUMNS are required to define a new model.


#### DATABASE_NAME_KEY

Database name key in the config file

- Type: `string`
- Default: `'default'`


#### TABLE

Database's Table the model works with

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

> This column is ignored by update()

- Type: `string|null`
- Default: `'id'`


#### OPTIONAL_COLUMNS

List of optional columns

> List all columns which have a default value (e.g. timestamp) or are nullable.
  AUTO_INCREMENT is always optional and does not need to be here.

- Type: `string[]`


#### FOREIGN_KEYS

Foreign Keys map

> A map of columns in the curent model which point to a column in another model

- Type: `array[]`
- Example:

```php
<?php

const FOREIGN_KEYS = [
    'local_column' => [
        'Fully\Qualified\ClassName',
        'foreign_column'
    ],
];
```


#### READ_ONLY

If `create()`, `update()` and `delete()` are disabled

- Type: `boolean`
- Default: `false`


#### SOFT_DELETE

If `delete()` actually removes the row or if it changes a column

> It defines the column affected by the soft delete

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


# TODO

- [ ] Real World tests
- [ ] Add more Models


[Model]: src/Model.php
[ModelIterator]: src/ModelIterator.php

[where_clause]: https://medoo.in/api/where

[catfan/Medoo]: https://github.com/catfan/Medoo
