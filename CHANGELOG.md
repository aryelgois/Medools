# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html)\*.

> \* Until 3.0.0, patch versions equal to 0 were omitted.


## [Unreleased]

### Added

### Changed

### Deprecated

### Removed

### Fixed

### Security


## [5.1.0] - 2018-05-28

### Added
- Repository title
- Note about foreign classes
- `getColumns()`
- `getTypedColumns()`
- `addColumnTypeKeys()`

### Changed
- `COLUMNS` may define prefered data type

### Fixed
- `getRequiredColumns()` was returning a non sequential index
- Comparison operator
- Typos


## [5.0.0] - 2018-03-29

### Added
- `getStampColumns()`
- `isDeleted()`

### Changed
- Update dependencies
- Rewrite MedooConnection
- Update config file example
- `save()` and `update()` always reload from Database to ensure data integrity
  and have better return values
- `delete()` only resets the model and removes it from from ModelManager if
  successful
- All `STAMP_COLUMNS` are implicitly optional

### Removed
- `dataCleanup()`

### Fixed
- Indentation
- `updateStampColumns()` subset filtering
- `save()`


## [4.3.0] - 2018-03-08

### Changed
- Update dependencies:
  - [catfan/Medoo]
  - [aryelgois/utils]
- ReadOnlyModelException extends `aryelgois\Utils\Exceptions\ReadOnlyException`


## [4.2.0] - 2018-02-25

### Added
- Links in README
- Model methods:
  - `getData()`

### Changed
- Update [aryelgois/utils]

### Fixed
- `jsonSerialize()` DocBlock and description


## [4.1.1] - 2018-02-22

### Changed
- Rename `getFormatedDocument()` to `getFormattedDocument()`

### Fixed
- Rename `formated` to `formatted`


## [4.1.0] - 2018-02-17

### Added
- Model methods:
  - `getDocumentType()`
  - `getFormatedDocument()`


## [4.0.0] - 2018-02-17

### Added
- Model `fill()` example
- Model methods:
  - `checkUnknownColumn()`
  - `checkReadOnly()`
  - `getIterator()`
  - `getRequiredColumns()`
  - `isFresh()`
  - `onColumnChange()`
  - `undo()`

### Changed
- Update [aryelgois/utils]
- Forbid assigning a fresh foreign model
- `validateHook()` only receives the data to be validated
- Person methods
- Rename Hook to Event
- Improve exceptions

### Removed
- `setMultiple()`

### Fixed
- ModelIterator and ModelManager `__construct()`: Ensure a model class is used,
  and not a model instance
- Delete fresh soft model


## [3.1.1] - 2018-02-09

### Fixed
- Warning for `array_flip()` in [aryelgois/utils] `Utils::arrayBlacklist()` when
  `AUTO_INCREMENT` is null


## [3.1.0] - 2018-02-09

### Added
- README Index
- Sections about `Reusing models` and `Other methods`
- Model methods:
  - `fill()`
  - `getChangedColumns()`
  - `getInstance()`
  - `onFirstSaveHook()`
  - `onSaveHook()`

### Changed
- Move changelog to its own file
- Move `Reloading the model` section to Other methods
- Move paragraphs from Advanced section to Other methods
- Header level in Advanced section

### Deprecated
- Model `setMultiple()`

### Removed
- pt_BR section in README


## [3.0] - 2018-01-31

### Added
- Quote about [indirection]

### Removed
- FullAddress model

### Fixed
- Previous changelog


## [3.0-alpha] - 2018-01-31

### Added
- Year 2018 in LICENSE
- Models are restored to ModelManager after `unserialize()`
- Models can be converted `toArray()`
- Constant `STAMP_COLUMNS`
- Columns with timestamp controlled by SQL Database can be ignored

### Changed
- Bump [catfan/Medoo] version
- Model reorganized
- Rename `DATABASE_NAME_KEY` to `DATABASE`
- Foreigns are loaded on demand
- `SOFT_DELETE` is implicitly optional
- ModelManager `$models` is private
- FullAddress went one subnamespace upper
- README partially rewritten/reorganized
- composer.json `suggest`

### Deprecated
- FullAddress model

### Removed
- Some models in the namespace `aryelgois\Medools\Models\Address`

### Fixed
- Problem when saving a model without a value for its soft delete column, which
  is controlled by `SOFT_DELETE_MODE`, the expected was to work
- After setting a column to null, `__get()` returned the old value


## [2.1] - 2018-01-06

### Added
- `Medools` keyword
- composer.json `suggest` key

### Deprecated
- Namespace `aryelgois\Medools\Models\Address`, in favor to the same one in
  [`aryelgois\Databases\Models\Address`][aryelgois/databases]

### Fixed
- README Install section
- composer.json `autoload` indentaion
- Backslash in DocBlocks


## [2.0] - 2017-11-18

### Added
- ModelManager. This class provides a way to reduce object duplication, keeping
  a track of loaded models, which are reused when referenced as foreign keys
- Models are JsonSerializable, so you can simply pass a model to `json_encode()`

### Changed
- Bump [aryelgois/utils] version
- ModelIterator uses MedooConnection
- Model methods. The most notable are `get()` and `set()`, replaced with PHP
  magic methods, and `getForeign()`, integrated with `__get()` to create a chain

### Fixed
- README
- Normalize some variable names
- Make MedooConnection abstract


## [1.0] - 2017-11-09

> I should have started from 0.1.0..

### Added
- Dependency [aryelgois/utils]
- README
- Base classes: Model, MedooConnection, ModelIterator
- Internal classes: Exceptions and Traits
- Example models: Address and Person
- Example of config file

### Changed
- Project name changed from `medoo-wrapper` to `Medools`. It is a mix of
  [Medoo][catfan/Medoo] and mode**ls**

### Removed
- DatabaseObject.php


[Unreleased]: https://github.com/aryelgois/Medools/compare/v5.1.0...develop
[5.1.0]: https://github.com/aryelgois/Medools/compare/v5.0.0...v5.1.0
[5.0.0]: https://github.com/aryelgois/Medools/compare/v4.3.0...v5.0.0
[4.3.0]: https://github.com/aryelgois/Medools/compare/v4.2.0...v4.3.0
[4.2.0]: https://github.com/aryelgois/Medools/compare/v4.1.1...v4.2.0
[4.1.1]: https://github.com/aryelgois/Medools/compare/v4.1.0...v4.1.1
[4.1.0]: https://github.com/aryelgois/Medools/compare/v4.0.0...v4.1.0
[4.0.0]: https://github.com/aryelgois/Medools/compare/v3.1.0...v4.0.0
[3.1.1]: https://github.com/aryelgois/Medools/compare/v3.1.0...v3.1.1
[3.1.0]: https://github.com/aryelgois/Medools/compare/v3.0...v3.1.0
[3.0]: https://github.com/aryelgois/Medools/compare/v3.0-alpha...v3.0
[3.0-alpha]: https://github.com/aryelgois/Medools/compare/v2.1...v3.0-alpha
[2.1]: https://github.com/aryelgois/Medools/compare/v2.0...v2.1
[2.0]: https://github.com/aryelgois/Medools/compare/v1.0...v2.0
[1.0]: https://github.com/aryelgois/Medools/compare/2816a9e56507e333744aaedb2e7898bd423e4211...v1.0

[aryelgois/databases]: https://github.com/aryelgois/databases
[aryelgois/utils]: https://github.com/aryelgois/utils
[catfan/Medoo]: https://github.com/catfan/Medoo

[indirection]: https://en.wikipedia.org/wiki/Indirection
