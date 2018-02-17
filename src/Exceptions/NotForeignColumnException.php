<?php
/**
 * This Software is part of aryelgois/Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools\Exceptions;

/**
 * A model column does not have a foreign constraint, but is used as if it had
 *
 * @author Aryel Mota Góis
 * @license MIT
 * @link https://www.github.com/aryelgois/Medools
 */
class NotForeignColumnException extends \InvalidArgumentException
{
    public function __construct(
        string $model,
        string $column,
        Throwable $previous = null
    ) {
        $message = "$model (`$column`) has no Foreign Key constraint";

        parent::__construct($message, 0, $previous);
    }
}
