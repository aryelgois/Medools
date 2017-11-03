<?php
/**
 * This Software is part of aryelgois\Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools\Models\Address;

use aryelgois\Medools;

/**
 * An Address model to reference a place in the world
 *
 * The submodels relation is:
 * County -> State -> Country
 *
 * @author Aryel Mota Góis
 * @license MIT
 * @link https://www.github.com/aryelgois/Medools
 */
class Address
{
    /**
     * County submodel
     *
     * var County
     */
    public $county;

    /**
     * State submodel
     *
     * var State
     */
    public $state;

    /**
     * Country submodel
     *
     * var Country
     */
    public $country;

    /**
     * Creates a new Address model
     *
     * @param integer $county_id A County Id
     *
     * @throws \RuntimeException If can not load submodels
     */
    public function __construct($county_id)
    {
        $this->county  = new County($county_id);
        $this->state   = new State($this->county->get('state'));
        $this->country = new Country($this->state->get('country'));

        if (!$this->country->isValid()) {
            throw new \RuntimeException('Could not load submodels');
        }
    }
}
