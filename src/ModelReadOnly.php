<?php

namespace aryelgois\Medools;

/**
 * Fills some Model's abstract methods, so it behaves as read-only
 *
 * @author Aryel Mota Góis
 * @license MIT
 * @link https://www.github.com/aryelgois/Medools
 */
trait ModelReadOnly
{
    /**
     * Ignores create
     *
     * @param mixed $data Unused
     *
     * @return false
     */
    public static function create($data = null)
    {
        return false;
    }

    /**
     * Ignores update
     *
     * @return false
     */
    public function update()
    {
        return false;
    }

    /**
     * Ignores delete
     *
     * @return false
     */
    public function delete()
    {
        return false;
    }
}
