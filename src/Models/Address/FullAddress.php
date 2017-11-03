<?php
/**
 * This Software is part of aryelgois\Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools\Models\Address;

use aryelgois\Medools;

/**
 * A Full Address model to reference a specific place in the world
 *
 * This class allows you to use more specific information about the Address,
 * which should be provided by your clients.
 *
 * @author Aryel Mota Góis
 * @license MIT
 * @link https://www.github.com/aryelgois/Medools
 */
abstract class FullAddress extends Medools\Model
{
    const TABLE = 'full_addresses';

    const COLUMNS = [
        'id',
        'county',
        'neighborhood',
        'place',
        'number',
        'zipcode',
        'detail',
        'update'
    ];

    /**
     * Address submodel
     *
     * @var Address
     */
    protected $address;

    /**
     * After parent, does loadAddress()
     *
     * @see parent::load()
     *
     * @return boolean For success or failure
     */
    public function load($where)
    {
        if (parent::load($where)) {
            return static::loadAddress();
        }
        return false;
    }

    /**
     * Loads Address submodel with model's county data
     *
     * @return boolean For success or failure
     */
    protected function loadAddress()
    {
        try {
            $this->address = new Address($this->get('county'));
        } catch (\RuntimeException $e) {
            $this->address = null;
            $this->valid = false;
            return false;
        }
        return true;
    }

    /**
     * After parent, does loadAddress() if required
     *
     * @see parent::set()
     *
     * @return boolean For success or failure
     */
    public function set($column, $value)
    {
        if (parent::set($column, $value)) {
            if ($column == 'county') {
                return static::loadAddress();
            }
            return true;
        }
        return false;
    }
}
