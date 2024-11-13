[![Software License](https://img.shields.io/badge/License-AGPLv3-green.svg?style=flat-square)](LICENSE) [![Bludit 3.15.x](https://img.shields.io/badge/Bludit-3.15.x-blue.svg?style=flat-square)](https://bludit.com) [![Bludit 3.16.x](https://img.shields.io/badge/Bludit-3.16.x-blue.svg?style=flat-square)](https://bludit.com)

# Are We Open (are-we-open) Plugin for Bludit

This is a availability plugin for Bludit 3.15.x and 3.16.x. Later 3.x versions may work.

## Description

This plugin allows you to display availability and/or business operating open/closed notices on your website.

You can display the status almost anywhere in Bludit.

_The plugin contains no tracking code of any kind_

## Demo

You can see the plugin in action on [bludit-bs5simplyblog.joho.se/areweopen](https://bludit-bs5simplyblog.joho.se/areweopen)

## Requirements

Bludit version 3.15.x or 3.16.x

## Installation

1. Download the latest release from the repository or GitHub
2. Extract the zip file into a folder, such as `tmp`
3. Upload the `are-we-open` folder to your web server or hosting and put it in the `bl-plugins` folder where Bludit is installed
4. Go your Bludit admin page
5. Klick on Plugins and activate the `Are We Open` plugin

## Usage

Simply put `[areweopen_open]your content[/areweopen_open]` and `[areweopen_closed]your content[/areweopen_closed]` somewhere in your content.

The plugin will respect `<pre>..</pre>` and not parse for the shortcodes in that HTML block.

## Other things I've created for Bludit

* [BS5Docs](https://bludit-bs5docs.joho.se), a fully featured Bootstrap 5 documentation theme for Bludit
* [BS5SimplyBlog](https://bludit-bs5simplyblog.joho.se), a fully featured Bootstrap 5 blog theme for Bludit
* [BS5Plain](https://bludit-bs5plain.joho.se), a simplistic and clean Bootstrap 5 blog theme for Bludit
* [Chuck Norris Quotes](https://github.com/joho1968/bludit-chucknorrisquotes), provides random Chuck Norris quotes for your Bludit page content
* [What's Up](https://github.com/joho1968/bludit-whats-up), a calendar agenda display plugin

## Changelog

### 1.0.0 (2024-10-25)
* Initial release

### 1.0.1 (2024-11-13)
* Corrections to Swedish translation
* Corrections to `README.md`
* Better escaping of strings in plugin configuration

## Other notes

This plugin has only been tested with PHP 8.1.x, but should work with other versions too. If you find an issue with your specific PHP version, please let me know and I will look into it.

## License

Please see [LICENSE](LICENSE) for a full copy of AGPLv3.

Copyright 2024 [Joaquim Homrighausen](https://github.com/joho1968); all rights reserved.

This file is part of are-we-open. are-we-open is free software.

are-we-open is free software: you may redistribute it and/or modify it  under
the terms of the GNU AFFERO GENERAL PUBLIC LICENSE v3 as published by the
Free Software Foundation.

are-we-open is distributed in the hope that it will be useful, but WITHOUT
ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
FITNESS FOR A PARTICULAR PURPOSE. See the GNU AFFERO GENERAL PUBLIC LICENSE
v3 for more details.

You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE v3
along with the are-we-open package. If not, write to:
```
The Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor
Boston, MA  02110-1301, USA.
```

## Credits

The Are We Open Plugin for Bludit was written by Joaquim Homrighausen while converting :coffee: into code.

Kudos to [Diego Najar](https://github.com/dignajar) for [Bludit](https://bludit.com) :blush:

### Whatever

Commercial support and customizations for this plugin is available from WebbPlatsen i Sverige AB.

If you find this Bludit add-on useful, feel free to donate, review it, and or spread the word :blush:

If there is something you feel to be missing from this Bludit add-on, or if you have found a problem with the code or a feature, please do not hesitate to reach out to bluditcode@webbplatsen.se.
