<?php
/**
 * This Software is part of aryelgois\Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools\Models;

use aryelgois\Medools;

/**
 * An Address object to reference a place in the world
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
    /* This model is supposed to be read-only */
    use Medools\Traits\ReadOnly;

    const DATABASE_NAME_KEY = 'address';

    /*
     * This class does not define the const DATABASE_TABLE because it uses more
     * than one table
     */

    /**
     * Columns fetched from `counties`
     *
     * @const string[]
     */
    const COLUMNS_COUNTY = ['id', 'state', 'name'];

    /**
     * Columns fetched from `states`
     *
     * @const string[]
     */
    const COLUMNS_STATE = ['id', 'country', 'code', 'name'];

    /**
     * Columns fetched from `countries`
     *
     * @const string[]
     */
    const COLUMNS_COUNTRY = [
        'id',
        'code_a2',
        'code_a3',
        'code_number',
        'name_en',
        'name_local'
    ];

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

        if ($address = $this->readAddress($where)) {
            $this->data = $address;
            $this->valid = true;
        } else {
            $this->valid = false;
        }

        return $this->valid;
    }

    /**
     * Returns Address' Id
     *
     * @param string $from Specifies where is to get the id from
     *
     * @return integer[] If object was created successfuly
     *                   keys: 'country', 'state', 'county'
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
     * Reads an Address data from the Database
     *
     * It will ask for the county, state and country entries, based on the
     * county index, because it's table is in the tip of the tables chain in the
     * Database.
     *
     * @param mixed[] $where_county Medoo where clause for `counties` table
     *                              Everything will be fetched from this
     *
     * @return array[] With fetched data
     * @return false   On failure
     */
    protected function readAddress($where_county)
    {
        $county = $this->database->get(
            'counties',
            self::COLUMNS_COUNTY,
            $where_county
        );

        $state = $this->database->get(
            'states',
            self::COLUMNS_STATE,
            ['id' => $county['state']]
        );

        $country = $this->database->get(
            'countries',
            self::COLUMNS_COUNTRY,
            ['id' => $state['country']]
        );

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
