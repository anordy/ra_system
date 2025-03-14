<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Services\Workflow\Exception;

/**
 * Base RuntimeException for the Workflow component.
 *
 * @author Alain Flaus <alain.flaus@gmail.com>
 */
class RuntimeException extends \RuntimeException implements ExceptionInterface
{
}
