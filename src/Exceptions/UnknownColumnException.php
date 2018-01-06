<?php
/**
 * This Software is part of aryelgois/Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools\Exceptions;

/**
 * When the model is requested for a column it does not have
 *
 * @author Aryel Mota Góis
 * @license MIT
 * @link https://www.github.com/aryelgois/Medools
 */
class UnknownColumnException extends \InvalidArgumentException
{
    use \aryelgois\Medools\Traits\ColumnsException;
}
