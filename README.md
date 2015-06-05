# DataTablePlus [![Total Downloads](https://img.shields.io/packagist/dt/khill/datatableplus.svg?style=plastic)](https://packagist.org/packages/khill/datatableplus) [![License](https://img.shields.io/packagist/l/khill/datatableplus.svg?style=plastic)](http://opensource.org/licenses/MIT) [![PayPayl](https://img.shields.io/badge/paypal-donate-yellow.svg?style=plastic)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=FLP6MYY3PYSFQ)

DataTablePlus is an extension for working with datatables in Lavacharts


Stable:
[![Current Release](https://img.shields.io/github/release/kevinkhill/datatableplus.svg?style=plastic)](https://github.com/kevinkhill/datatableplus/releases)
[![Build Status](https://img.shields.io/travis/kevinkhill/datatableplus/master.svg?style=plastic)](https://travis-ci.org/kevinkhill/datatableplus)
[![Coverage Status](https://img.shields.io/coveralls/kevinkhill/datatableplus/master.svg?style=plastic)](https://coveralls.io/r/kevinkhill/datatableplus?branch=master)



Package Features
================
- Converting CSV files to DataTables
- Converting DataTables to CSV
- Parsing Eloquent Collections to DataTables

### This is still a very alpha package, but it works.


Installing
----------
In your project's main ```composer.json``` file, add these lines to the requirements:

  ```
  "khill/lavacharts": "dev-3.0"
  "khill/datatableplus": "dev-master"
  ```
Note: This package extends DataTables in Lavacharts. Without Lavacharts, this package does nothing useful.


Run Composer to install Lavacharts:

  ```
  composer update
  ```

Use the Lava#DataTable() method as usual, but you will get the extended version with extra features automatically.
