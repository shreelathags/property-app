
## Testing

Prerequisites:
In file database/migrations/2022_11_11_190933_create_properties_table.php, comment line #43 before running tests.
SQLite does not support fulltext indexes, so this line needs to be commented for feature tests to work.
Rememeber to uncomment this line after the test
