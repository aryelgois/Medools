<?php
/**
 * This Software is part of aryelgois/Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools\Traits;

/**
 * An Exception which expects to receive a list of columns
 *
 * @author Aryel Mota Góis
 * @license MIT
 * @link https://www.github.com/aryelgois/Medools
 */
trait ColumnsException
{
    public function __construct(
        string $model,
        $columns,
        Throwable $previous = null
    ) {
        $columns = implode("', '", (array) $columns);
        $message = "$model does not have '$columns'";

        parent::__construct($message, 0, $previous);
    }
}
