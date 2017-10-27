<?php
/**
 * This Software is part of aryelgois\Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools\Traits;

/**
 * Fills some Model's abstract methods, so it behaves as read-only
 *
 * @todo if strict mode is active, these methods should throw an error
 *
 * @author Aryel Mota Góis
 * @license MIT
 * @link https://www.github.com/aryelgois/Medools
 */
trait ReadOnly
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
