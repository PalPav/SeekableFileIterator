<?php

use \RuntimeException;

class SeekableFileIterator implements \SeekableIterator {

    private $handle;
    private $mode = 'r';
    private $fileName;
    private $buffer = NULL;
    private $handlePosition = 0;
    private $fileSize = 0;


    /**
     * SeekableFileIterator constructor.
     * @param $fileName
     * @throws RuntimeException
     */

    function __construct($fileName) {
        $this->handle = fopen($fileName, $this->mode);

        if (!$this->handle) {
            throw new RuntimeException('Cannot open file ' . $fileName);
        }

        $this->fileName = $fileName;
        $this->fileSize = filesize($fileName);
    }


    /**
     * Return the current element
     * @return mixed
     * @throws RuntimeException
     */
    public function current() {

        if (is_null($this->buffer)) {

            if (feof($this->handle)) {
                throw new RuntimeException(sprintf('End of file %s' . $this->fileName));
            }
            
            $this->handlePosition = ftell($this->handle);
            
            $this->buffer = fread($this->handle,1);
           
        }

        return $this->buffer;
    }



    /**
     * Move forward to next element
     */
    public function next() {
        $this->buffer = NULL;
    }

    /**
     * Return the current handle position
     */
    public function key() {
        return $this->handlePosition;
    }


    /**
     * Checks if current position is valid
     * @return boolean
     */
    public function valid() {
        return !feof($this->handle);
    }

    /**
     * Rewind the Iterator to the first element
     * @throws RuntimeException
     */
    public function rewind() {

        $this->buffer = NULL;
        $this->handlePosition = 0;

        if (!rewind($this->handle)) {
             throw new RuntimeException(sprintf('Cannot rewind file %s', $this->fileName));
        }
    }

    /**
     * Seeks to a position
     * @param int $position
     * @throws \OutOfBoundsException
     */
    public function seek($position) {

        if ($position >= 0 && $position <= $this->fileSize) {
            fseek($this->handle,$position,SEEK_SET);
        } else {
           throw new \OutOfBoundsException(sprintf('Seek position %d is out of bounds', $position));
        }

        $this->buffer = NULL;
        $this->handlePosition = $position;


    }

    public function __destruct() {
        if ($this->handle) {
            fclose($this->handle);
        }
    }
}




