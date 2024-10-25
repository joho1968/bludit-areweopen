[![Software License](https://img.shields.io/badge/License-AGPLv3-green.svg?style=flat-square)](LICENSE) [![PHP 7](https://img.shields.io/badge/PHP7-blue.svg?style=flat-square)](https://php.net) [![PHP 8](https://img.shields.io/badge/PHP8-blue.svg?style=flat-square)](https://php.net)

# Availability class for PHP (AvailableTime)

## Description

This class provides the means to check a number of parameters against a given time and date to determine availability.

It could, for example, be used to show business operating hours, or a simple booking availability indicator.

## Demo

You can see the code in action in the form of a Bludit CMS plugin on [bludit-bs5simplyblog.joho.se/areweopen](https://bludit-bs5simplyblog.joho.se/areweopen)

## Requirements

PHP 7.x, PHP 8.x, and so on ... :blush:

## Installation

1. Download the `availabletime.class.php` file.
2. Alternatively, also download `test_available_time.php` file
3. Include it in your project and play with it

## Usage

See the file `test_available_time.php` for sample usage.

### Time and date range

For the `exception_times` parameter, you may use specific dates (YYYYMMDD), or a range of dates where either start or end can be omitted.

To specify a range, use `YYYYMMDD-YYYYMMDD`, `YYYYMMDD-`, `-YYYYMMDD`. The `YYYY` part may also be expressed as `????` to mean every year on the specified month and day.

For the daily schedule, time slots are specified as `HHMM-HHMM`, `HHMM-` and/or `-HHMM`.

The code assumes dates to be specified as `YYYYMMDD` and times as `HHMM` (24h format)

## Changelog

### 1.0.1 (2024-10-24)
* Added support for wildcard year parameter in exception dates

### 1.0.0 (2024-10-24)
* Initial release

## Other notes

This code has only been tested with PHP 8.1.x, but should work with other versions too. If you find an issue with your specific PHP version, please let me know and I will look into it.

## License

Please see [LICENSE](LICENSE) for a full copy of AGPLv3.

Copyright 2024 [Joaquim Homrighausen](https://github.com/joho1968); all rights reserved.

This file is part of AvailableTime. AvailableTime is free software.

AvailableTime is free software: you may redistribute it and/or modify it  under
the terms of the GNU AFFERO GENERAL PUBLIC LICENSE v3 as published by the
Free Software Foundation.

AvailableTime is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
FITNESS FOR A PARTICULAR PURPOSE. See the GNU AFFERO GENERAL PUBLIC LICENSE
v3 for more details.

You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE v3
along with the AvailableTime package. If not, write to:
```
The Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor
Boston, MA  02110-1301, USA.
```

## Disclaimer

Disclaimer: There's nothing magic about this code and there are many other, and, probably, better ways to accomplish this

## Credits

The AvailableTime class was written by Joaquim Homrighausen while converting :coffee: into code.

## Note

If there is something you feel to be missing from this code, or if you have found a problem with the code or a feature, please do not hesitate to create a new issue on GitHub.
