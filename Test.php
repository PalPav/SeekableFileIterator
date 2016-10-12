<?php

//выводит строку abcdefghijk lm nopqrstuvw xyz abaa

$iterator = new SeekableFileIterator('test.txt');

foreach ($iterator as $byte) {
    echo $byte;
}

$iterator->rewind();

echo $iterator->current();

$iterator->next();

echo $iterator->current();

$iterator->seek(0);

echo $iterator->current();
