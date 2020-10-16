<?php
/*
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A
 * PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * (c) 2020.
 * @see https://github.com/GustavoSantosBr/api-core
 * @author Gustavo Freze de Araujo Santos <gustavo.freze@gmail.com>
 *
 */

declare(strict_types=1);

namespace ApiCore\Validation;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class Violations
 * @package ApiCore\Validation
 */
abstract class Violations
{
    /**
     * @param ValidatorInterface $validator
     * @param ObjectCoreInterface $objectCore
     * @return array
     */
    public static function getViolations(ValidatorInterface $validator, ObjectCoreInterface $objectCore): array
    {
        $propertyConstraints = $validator->validate($objectCore);
        $violations = [];

        foreach ($propertyConstraints as $value) {
            if (!empty($value)) {
                /** @var ConstraintViolation $value */
                array_push($violations, $value->getMessage());
            }
        }
        return $violations;
    }
}