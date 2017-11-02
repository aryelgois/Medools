<?php
/**
 * This Software is part of aryelgois\Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools\Models;

use aryelgois\Medools;

/**
 * An Address model to reference a place in the world
 *
 * It is built on top of aryelgois\databases\Address, which means it expects
 * you have a database following that scheme in your server.
 *
 * @see https://www.github.com/aryelgois/databases
 *
 * @author Aryel Mota Góis
 * @license MIT
 * @link https://www.github.com/aryelgois/Medools
 */
class Address extends Medools\Model
{
    const DATABASE_NAME_KEY = 'address';

    const TABLES = [
        'counties' => ['id', 'state', 'name'],
        'states' => ['id', 'country', 'code', 'name'],
        'countries' => [
            'id',
            'code_a2',
            'code_a3',
            'code_number',
            'name_en',
            'name_local'
        ],
    ];

   const READ_ONLY = true;

    /**
     * Returns Address' Id
     *
     * @param string $from Specifies where is to get the id from
     *
     * @return integer[] If model is valid. keys: 'country', 'state', 'county'
     * @return integer   If the parameter matched a key
     * @return null      If any Id is not found
     */
    public function getId($from = null)
    {
        if (!isset(
            $this->data['country']['id'],
            $this->data['state']['id'],
            $this->data['county']['id']
        )) {
            return null;
        }

        $result = [
            'country' => $this->data['country']['id'],
            'state' => $this->data['state']['id'],
            'county' => $this->data['county']['id']
        ];
        if (array_key_exists($from, $result)) {
            return $result[$from];
        }
        return $result;
    }

    /**
     * Reads an Address from the Database
     *
     * @param mixed[] $where \Medoo\Medoo where clause for `counties`
     *
     * @return boolean For success or failure
     */
    public function read($where)
    {
        $this->reset();
        $this->valid = false;

        if ($address = $this->readAddress($where)) {
            $this->data = $address;
            $this->valid = true;
        }

        return $this->valid;
    }

    /**
     * Reads Address' data from the Database
     *
     * It will ask for the county, state and country entries, based on the
     * county index, because it's table is in the tip of the tables chain in the
     * Database.
     *
     * @param mixed[] $where_county Medoo where clause for `counties`
     *                              Everything will be fetched from this table
     *
     * @return array[] With fetched data
     * @return false   On failure
     */
    protected function readAddress($where_county)
    {
        $county  = $this->readEntry('counties',  $where_county);
        $state   = $this->readEntry('states',    ['id' => $county['state']]);
        $country = $this->readEntry('countries', ['id' => $state['country']]);

        if (!($county && $state && $country)) {
            return false;
        }

        unset($county['state'], $state['country']);

        return [
            'county' => $county,
            'state' => $state,
            'country' => $country
        ];
    }
}
