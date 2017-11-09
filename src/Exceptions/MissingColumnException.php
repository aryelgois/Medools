<?php
/**
 * This Software is part of aryelgois\Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools\Exceptions;

/**
 * There are missing columns when trying to save the model
 *
 * @author Aryel Mota Góis
 * @license MIT
 * @link https://www.github.com/aryelgois/Medools
 */
class MissingColumnException extends \RuntimeException
{
    use \aryelgois\Medools\Traits\ColumnsException;
}
