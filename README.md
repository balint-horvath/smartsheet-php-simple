# SimpleSmartsheet
SimpleSmartsheet makes easier to do magic with Smartsheet.
Works fine with Smartsheet API v2.0.

## Generating HTML Documentation from a sheet

```php
header("Content-type: text/html; chardet=utf-8");

require 'SimpleSmartsheet.php';

$SheetID = "";      // ID of your Smartsheet's sheet
$APIKey = "";       // Smartsheet API Key

$SS = new SimpleSmartsheet($APIKey);
```