<?php
/**
 * This Software is part of aryelgois\Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools\Models;

use aryelgois\Medools;

/**
 * Get every Address entry from the Database
 *
 * Useful to fill a form
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
class Addresses extends Medools\Model
{
    const DATABASE_NAME_KEY = 'address';

    const READ_ONLY = true;

    /*
     * This class does not define the const TABLES because it uses Address'
     */

    /**
     * Fulfills parent class requirement about this method
     *
     * @param mixed $where Unused
     *
     * @throws BadMethodCallException
     */
    public function read($where)
    {
        $msg = 'Invalid method. Use readCountries(), readStates() or '
             . 'readCounties() instead';
        throw new \BadMethodCallException($msg);
    }

    /**
     * Fetches every Country from the Database and caches it in the model
     *
     * @param boolean $reload If should reload the cache
     *
     * @return array[] With fetched columns
     */
    public function readCountries($reload = false)
    {
        $database = $this->getDatabase();

        if ($reload || !isset($this->data['countries'])) {
            $this->data['countries'] = $database->select(
                'countries',
                Address::TABLES['countries']
            );
        }

        return $this->data['countries'];
    }

    /**
     * Fetches every State from a Country and caches it in the model
     *
     * @param integer $country_id Country Id to reduce the query
     * @param boolean $reload     If should reload the cache
     *
     * @return array[] With fetched columns
     */
    public function readStates($country_id, $reload = false)
    {
        $database = $this->getDatabase();

        if ($reload || !isset($this->data['states'][$country_id])) {
            $this->data['states'][$country_id] = $database->select(
                'states',
                Address::TABLES['states'],
                ['country' => $country_id]
            );
        }

        return $this->data['states'][$country_id];
    }

    /**
     * Fetches every County from a State and caches it in the model
     *
     * @param integer $state_id State Id to reduce the query
     * @param boolean $reload   If should reload the cache
     *
     * @return array[] With fetched columns
     */
    public function readCounties($state_id, $reload = false)
    {
        $database = $this->getDatabase();

        if ($reload || !isset($this->data['counties'][$state_id])) {
            $this->data['counties'][$state_id] = $database->select(
                'counties',
                Address::TABLES['counties'],
                ['state' => $state_id]
            );
        }

        return $this->data['counties'][$state_id];
    }

   /**
    * Clears model cache
    */
   protected function reset()
   {
       $this->data = [];
   }
}
