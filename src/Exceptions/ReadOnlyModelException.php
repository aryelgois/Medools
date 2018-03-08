<?php
/**
 * This Software is part of aryelgois/Medools and is provided "as is".
 *
 * @see LICENSE
 */

namespace aryelgois\Medools\Exceptions;

use aryelgois\Utils;

/**
 * A model is configured as read-only, but tries to change its data
 *
 * @author Aryel Mota Góis
 * @license MIT
 * @link https://www.github.com/aryelgois/Medools
 */
class ReadOnlyModelException extends Utils\Exceptions\ReadOnlyException
{}
