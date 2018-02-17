<?php
/**
 * This Software is part of aryelgois/Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools\Models;

use aryelgois\Utils;
use aryelgois\Medools;

/**
 * A Person model defines someone in the real world. It is a basic setup and
 * you must to extend for your use case.
 *
 * @author Aryel Mota Góis
 * @license MIT
 * @link https://www.github.com/aryelgois/Medools
 */
class Person extends Medools\Model
{
    const TABLE = 'people';

    const COLUMNS = ['id', 'name', 'document'];

    /**
     * Validates a document as Brazilian CPF or CNPJ
     *
     * @param boolean $document Data to be validated
     *
     * @return mixed[] With keys 'type' and 'valid'
     * @return false   If document is invalid
     * @return null    If document is not set
     */
    public static function documentValidate($document)
    {
        if (!isset($document)) {
            return null;
        }
        return Utils\Validation::document($document);
    }

    /**
     * Formats a document
     *
     * @param boolean $document Data to be formated
     * @param boolean $prepend  If should prepend the document type
     *
     * @return string Formatted document
     * @return string Unformatted document if it is invalid
     * @return null   If document is not set
     */
    public static function documentFormat($document, $prepend = false)
    {
        return Utils\Format::document($document, $prepend);
    }

    /**
     * Returns Person's document formated
     *
     * @param boolean $prepend @see documentFormat()
     *
     * @return integer Document type
     * @return false   If document is invalid or not set
     */
    public function getDocumentType()
    {
        return static::documentValidate($this->document)['type'] ?? false;
    }

    /**
     * Returns Person's document formated
     *
     * @param boolean $prepend @see documentFormat()
     *
     * @return ... same as documentFormat()
     */
    public function getFormatedDocument($prepend = false)
    {
        return static::documentFormat($this->document, $prepend);
    }

    /**
     * Called when a column is changed
     *
     * @return mixed New column value
     */
    protected function onColumnChange($column, $value)
    {
        if ($column == 'document') {
            $value = static::documentValidate($value)['valid'] ?? $value;
        }

        return $value;
    }
}
