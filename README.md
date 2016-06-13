# SimpleSmartsheet
SimpleSmartsheet is able to create HTML Documentation from Smartsheet.
Works fine with Smartsheet API v2.0.

## Initialization

```php
require 'SimpleSmartsheet.php';
$SS = new SimpleSmartsheet($APIKey);
```

## Generating HTML Documentation from a sheet

### Method
genHTMLDoc(SheetID, HeadingCol=0, DescriptionCol=1, HeadingDepth=1)

### Properties
* SheetID - ID of your Sheet
* HeadingCol - Column ID of headings
* DescriptionCol - Column ID of descriptions
* HeadingDepth - Start depth of heading (<h{HeadingDepth}>)

### Example
```php
$SS->genHTMLDoc($SheetID, 0, 1, 2)
```