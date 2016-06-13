# SimpleSmartsheet
SimpleSmartsheet is able to create HTML Documentation from Smartsheet.
Works fine with Smartsheet API v2.0.

## Initialization

```php
require 'SimpleSmartsheet.php';
$SS = new SimpleSmartsheet($APIKey);
```

Set proper write permissions to ./cache/ directory.

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

### Example Result

```HTML
<h2>Feature 1</h2>
    <h3>Subfeature 1.1</h3>
    <p>1.1 is an awesome feature.</p>
        <h4>Subsubfeature</h4>
        <p>1.1.1 is an awesome feature too.</p>
    <h3>Subfeature 1.2</h3>
    <p>1.2 is an awesome feature.</p>
    <h2>Feature 2</h2>
        <h3>Subfeature 2.1</h3>
        <p>2.1 is an awesome feature.</p>
    <h3>Subfeature 2.2</h3>
    <p>2.2 is an awesome feature.</p>
```