<?php
/**
 * This Software is part of aryelgois\Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools\Models;

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
    const DATABASE_NAME_KEY = 'my_app'; // example

    const TABLES = [
        'full_addresses' => [
            'id',
            'county',
            'neighborhood',
            'place',
            'number',
            'zipcode',
            'detail',
            'update'
        ],
    ];

    /**
     * Reads a FullAddress data from the Database
     *
     * @param mixed[] $where \Medoo\Medoo where clause
     *
     * @return boolean For success or failure
     */
    public function read($where)
    {
        $this->reset();
        $this->valid = false;

        if ($full_address = $this->readEntry('full_addresses', $where)) {
            $address = new Address();
            $address->read(['id' => $full_address['county']]);

            unset($full_address['county']);
            $full_address['address'] = $address;

            if ($address->isValid()) {
                $this->data = $full_address;
                $this->valid = true;
            }
        }

        return $this->valid;
    }
}
