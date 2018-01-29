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

    protected static $columns = [
        [
            'name' => 'id',
            'primary' => true,
            'auto_increment' => true,
        ],
        [
            'name' => 'name',
        ],
        [
            'name' => 'document',
        ],
    ];

    /**
     * Validates Person's document as Brazilian CPF or CNPJ
     *
     * @return mixed[] With keys 'type' and 'valid'
     * @return false   If document is invalid
     * @return null    If a document row was not found
     */
    public function documentValidate()
    {
        return Utils\Validation::document($this->document);
    }

    /**
     * Formats Person's Document
     *
     * @param boolean $prepend If should prepend the document name
     *
     * @return string Formatted document
     * @return string Unformatted document if it is invalid
     * @return null   If a document row was not found
     */
    public function documentFormat($prepend = false)
    {
        return Utils\Format::document($this->document, $prepend);
    }
}
