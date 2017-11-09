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
[catfan/Medoo]: https://github.com/catfan/Medoo
