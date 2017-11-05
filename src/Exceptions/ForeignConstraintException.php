<?php
/**
 * This Software is part of aryelgois\Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools\Exceptions;

use aryelgois\Medools;

/**
 * A model's foreign can not load due to invalid data in the table
 *
 * @author Aryel Mota Góis
 * @license MIT
 * @link https://www.github.com/aryelgois/Medools
 */
class ForeignConstraintException extends \RuntimeException
{
    public function __construct(
        string $model,
        string $column,
        Throwable $previous = null
    ) {
        $map = $model::FOREIGN_KEYS[$column] ?? null;
        $message = "Foreign Key constraint fails: "
                 . "$model (`$column`) -> $map[0] (`$map[1]`)";

        parent::__construct($message, 0, $previous);
    }
}
