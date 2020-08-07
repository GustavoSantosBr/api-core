<?php
/**
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

use ApiCore\Exception\Config;
use ApiCore\Exception\ExceptionCore;
use Http\StatusHttp;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ValidatorCore
 * @package ApiCore\Validation
 */
class ValidatorCore
{
    /**
     * @var ValidatorInterface
     */
    private $validation;

    public function __construct()
    {
        $this->validation = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
    }

    /**
     * @param ObjectCoreInterface|null $objectCore
     * @param string|null $messageError
     * @throws ExceptionCore
     */
    public function validateCore(?ObjectCoreInterface $objectCore, ?string $messageError = null): void
    {
        if (empty($objectCore)) {
            throw new ExceptionCore((new Config())
                ->setStatusCode(StatusHttp::BAD_REQUEST)
                ->setInternalMessageError($messageError));
        }

        $propertyConstraints = $this->validation->validate($objectCore);
        $violations = [];

        foreach ($propertyConstraints as $value) {
            if (!empty($value)) {
                /** @var ConstraintViolation $value */
                array_push($violations, $value->getMessage());
            }
        }

        if (count($violations) > 0) {
            throw new ExceptionCore((new Config())
                ->setStatusCode(StatusHttp::BAD_REQUEST)
                ->setMessageError($messageError)
                ->setArrayError($violations));
        }
    }
}