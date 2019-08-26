# Attachments

You may attach a file from memory or by a file path.

## From file path

```php
$mg->messages()->send('example.com', [
  'from'    => 'bob@example.com', 
  'to'      => 'sally@example.com', 
  'subject' => 'Test file path attachments', 
  'text'    => 'Test',
  'attachment' => [
    ['filePath'=>'/tmp/foo.jpg', 'filename'=>'test.jpg']
  ]
]);
```
## From memory

```php
// Some how load the file to memory
$binaryFile = '[Binary data]';

$mg->messages()->send('example.com', [
  'from'    => 'bob@example.com', 
  'to'      => 'sally@example.com', 
  'subject' => 'Test memory attachments', 
  'text'    => 'Test',
  'attachment' => [
    ['fileContent'=>$binaryFile, 'filename'=>'test.jpg']
  ]
]);
```

## Inline attachments

```php
$mg->messages()->send('example.com', [
  'from'    => 'bob@example.com', 
  'to'      => 'sally@example.com', 
  'subject' => 'Test inline attachments', 
  'text'    => 'Test',
  'inline' => [
    ['filePath'=>'/tmp/foo.jpg', 'filename'=>'test.jpg']
  ]
]);
```
