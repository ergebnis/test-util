# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

For a full diff see [`1.4.0...main`][1.4.0...main].

## [`1.4.0`][1.4.0]

For a full diff see [`1.3.0...1.4.0`][1.3.1...1.4.0].

### Added

* Added `DataProvider\StringProvider::withWhitespace()` ([#374]), by [@localheinz]

## [`1.3.1`][1.3.1]

For a full diff see [`1.3.0...1.3.1`][1.3.0...1.3.1].

### Fixed

* Started using `fakerphp/faker` instead of `fzaninotto/faker` ([#372]), by [@localheinz]

## [`1.3.0`][1.3.0]

For a full diff see [`1.2.0...1.3.0`][1.2.0...1.3.0].

### Added

* Added `DataProvider\ObjectProvider` ([#343]), by [@localheinz]
* Added `DataProvider\ResourceProvider` ([#344]), by [@localheinz]

## [`1.2.0`][1.2.0]

For a full diff see [`1.1.0...1.2.0`][1.1.0...1.2.0].

### Added

* Added `DataProvider\BooleanProvider` ([#326]), by [@localheinz]
* Added `DataProvider\NullProvider` ([#327]), by [@localheinz]
* Added `DataProvider\StringProvider` ([#328]), by [@localheinz]
* Added `DataProvider\IntProvider` ([#335]), by [@localheinz]
* Added `DataProvider\FloatProvider` ([#341]), by [@localheinz]

### Changed

* Renamed `DataProvider\BooleanProvider` to `DataProvider\BoolProvider` ([#334]), by [@localheinz]

## [`1.1.0`][1.1.0]

For a full diff see [`1.0.1...1.1.0`][1.0.1...1.1.0].

### Changed

* Added support for PHP 8.0 ([#302]), by [@localheinz]

## [`1.0.1`][1.0.1]

For a full diff see [`1.0.0...1.0.1`][1.0.0...1.0.1].

### Changed

* Dropped support for PHP 7.1 ([#295]), by [@localheinz]

## [`1.0.0`][1.0.0]

For a full diff see [`0.9.1...1.0.0`][0.9.1...1.0.0].

## [`0.9.1`][0.9.1]

For a full diff see [`0.9.0...0.9.1`][0.9.0...0.9.1].

### Fixed

* Brought back support for PHP 7.1 ([#155]), by [@localheinz]

## [`0.9.0`][0.9.0]

For a full diff see [`0.8.0...0.9.0`][0.8.0...0.9.0].

### Changed

* Renamed vendor namespace `Localheinz` to `Ergebnis` after move to [@ergebnis] ([#147]), by [@localheinz]

  Run

  ```
  $ composer remove localheinz/test-util
  ```

  and

  ```
  $ composer require ergebnis/test-util
  ```

  to update.

  Run

  ```
  $ find . -type f -exec sed -i '.bak' 's/Localheinz\\Test\\Util/Ergebnis\\Test\\Util/g' {} \;
  ```

  to replace occurrences of `Localheinz\Test\Util` with `Ergebnis\Test\Util`.

  Run

  ```
  $ find -type f -name '*.bak' -delete
  ```

  to delete backup files created in the previous step.

## [`0.8.0`][0.8.0]

For a full diff see [`0.7.0...0.8.0`][0.7.0...0.8.0].

### Changed

* Dropped support for PHP 7.1 ([#118]), by [@localheinz]
* Methods in `Helper` trait are now `static` ([#119]), by [@localheinz]
* Dropped support for `phpunit/phpunit:^6.0.0` ([#120]), by [@localheinz]
* Allowed installation with `phpunit/phpunit:^8.0.0` ([#122]), by [@localheinz]

[0.8.0]: https://github.com/ergebnis/test-util/releases/tag/0.8.0
[0.9.0]: https://github.com/ergebnis/test-util/releases/tag/0.9.0
[0.9.1]: https://github.com/ergebnis/test-util/releases/tag/0.9.1
[1.0.0]: https://github.com/ergebnis/test-util/releases/tag/1.0.0
[1.0.1]: https://github.com/ergebnis/test-util/releases/tag/1.0.1
[1.1.0]: https://github.com/ergebnis/test-util/releases/tag/1.1.0
[1.2.0]: https://github.com/ergebnis/test-util/releases/tag/1.2.0
[1.3.0]: https://github.com/ergebnis/test-util/releases/tag/1.3.0
[1.3.1]: https://github.com/ergebnis/test-util/releases/tag/1.3.1
[1.4.0]: https://github.com/ergebnis/test-util/releases/tag/1.4.0

[0.7.0...0.8.0]: https://github.com/ergebnis/test-util/compare/0.7.0...0.8.0
[0.8.0...0.9.0]: https://github.com/ergebnis/test-util/compare/0.8.0...0.9.0
[0.9.0...0.9.1]: https://github.com/ergebnis/test-util/compare/0.9.0...0.9.1
[0.9.1...1.0.0]: https://github.com/ergebnis/test-util/compare/0.9.1...1.0.0
[1.0.0...1.0.1]: https://github.com/ergebnis/test-util/compare/1.0.0...1.0.1
[1.0.1...1.1.0]: https://github.com/ergebnis/test-util/compare/1.0.1...1.1.0
[1.1.0...1.2.0]: https://github.com/ergebnis/test-util/compare/1.1.0...1.2.0
[1.2.0...1.3.0]: https://github.com/ergebnis/test-util/compare/1.2.0...1.3.0
[1.3.0...1.3.1]: https://github.com/ergebnis/test-util/compare/1.3.0...1.3.1
[1.3.1...1.4.0]: https://github.com/ergebnis/test-util/compare/1.3.1...1.4.0
[1.4.0...main]: https://github.com/ergebnis/test-util/compare/1.4.0...main

[#118]: https://github.com/ergebnis/test-util/pull/118
[#119]: https://github.com/ergebnis/test-util/pull/119
[#120]: https://github.com/ergebnis/test-util/pull/120
[#122]: https://github.com/ergebnis/test-util/pull/122
[#147]: https://github.com/ergebnis/test-util/pull/147
[#155]: https://github.com/ergebnis/test-util/pull/155
[#295]: https://github.com/ergebnis/test-util/pull/295
[#302]: https://github.com/ergebnis/test-util/pull/302
[#326]: https://github.com/ergebnis/test-util/pull/326
[#327]: https://github.com/ergebnis/test-util/pull/327
[#328]: https://github.com/ergebnis/test-util/pull/328
[#334]: https://github.com/ergebnis/test-util/pull/334
[#335]: https://github.com/ergebnis/test-util/pull/335
[#341]: https://github.com/ergebnis/test-util/pull/341
[#343]: https://github.com/ergebnis/test-util/pull/343
[#344]: https://github.com/ergebnis/test-util/pull/344
[#372]: https://github.com/ergebnis/test-util/pull/372
[#374]: https://github.com/ergebnis/test-util/pull/374

[@ergebnis]: https://github.com/ergebnis
[@localheinz]: https://github.com/localheinz
