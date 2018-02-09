# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html)\*.

> \* Until 3.0.0, patch versions equal to 0 were omitted.


## [Unreleased]

### Added
- Model `fill()`
- Model `getInstance()`
- Model hooks `onFirstSaveHook()` and `onSaveHook()`
- Model `getChangedColumns()`
- Section about Reusing models
- Section about Other methods
- README Index

### Changed
- Moved changelog to its own file
- Move `Reloading the model` section to Other methods
- Move paragraphs from Advanced section to Other methods
- Header level inside Advanced section

### Deprecated
- Model `setMultiple()`

### Removed

### Fixed

### Security


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


[Unreleased]: https://github.com/aryelgois/Medools/compare/v3.0...v3.x
[3.0]: https://github.com/aryelgois/Medools/compare/v3.0-alpha...v3.0
[3.0-alpha]: https://github.com/aryelgois/Medools/compare/v2.1...v3.0-alpha
[2.1]: https://github.com/aryelgois/Medools/compare/v2.0...v2.1
[2.0]: https://github.com/aryelgois/Medools/compare/v1.0...v2.0
[1.0]: https://github.com/aryelgois/Medools/compare/2816a9e56507e333744aaedb2e7898bd423e4211...v1.0

[aryelgois/databases]: https://github.com/aryelgois/databases
[aryelgois/utils]: https://github.com/aryelgois/utils
[catfan/Medoo]: https://github.com/catfan/Medoo

[indirection]: https://en.wikipedia.org/wiki/Indirection
