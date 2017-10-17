<?php
/**
 * This Software is part of aryelgois\MedooWrapper and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\MedooWrapper\Models;

use aryelgois\MedooWrapper;

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
 * @link https://www.github.com/aryelgois/medoo-wrapper
 */
abstract class Address extends MedooWrapper\DatabaseObject
{
    /*
     * This class does not define the const DATABASE_TABLE because it uses more
     * than one table
     */

    /**
     * Rows fetched from `counties`
     *
     * @const string[]
     */
    const ROWS_COUNTY = ['id', 'state', 'name'];

    /**
     * Rows fetched from `states`
     *
     * @const string[]
     */
    const ROWS_STATE = ['id', 'country', 'code', 'name'];

    /**
     * Rows fetched from `countries`
     *
     * @const string[]
     */
    const ROWS_COUNTRY = [
        'id',
        'code_a2',
        'code_a3',
        'code_number',
        'name_en',
        'name_local'
    ];

    /**
     * Creates a new Address object
     *
     * @param mixed[] $where_county Medoo where clause for `counties`
     */
    public function __construct($where_county)
    {
        parent::__construct();

        if ($address = $this->loadAddress($where_county)) {
            $this->data = $address;
            $this->valid = true;
        } else {
            $this->valid = false;
        }
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
     * Fetches an Address from the Database
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
    protected function loadAddress($where_county)
    {
        $county = $this->database->get(
            'counties',
            self::ROWS_COUNTY,
            $where_county
        );

        $state = $this->database->get(
            'states',
            self::ROWS_STATE,
            ['id' => $county['state']]
        );

        $country = $this->database->get(
            'countries',
            self::ROWS_COUNTRY,
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
