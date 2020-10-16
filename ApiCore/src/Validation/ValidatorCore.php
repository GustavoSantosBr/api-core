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
    private $validator;

    /**
     * @var array
     */
    private $errors = [];

    private const MESSAGE_ERROR = "O objeto está vazio.";

    public function __construct()
    {
        $this->validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
    }

    /**
     * @param ObjectCoreInterface|null $objectCore
     * @param int $statusCode
     * @throws ExceptionCore
     */
    public function validateCore(?ObjectCoreInterface $objectCore,
                                 int $statusCode = StatusHttp::UNPROCESSABLE_ENTITY): void
    {
        if (empty($objectCore)) {
            throw new ExceptionCore((new Config())
                ->setStatusCode($statusCode)
                ->setInternalMessageError(self::MESSAGE_ERROR));
        }

        $violations = Violations::getViolations($this->validator, $objectCore);

        if ($this->hasErrors($violations)) {
            throw new ExceptionCore((new Config())
                ->setStatusCode($statusCode)
                ->setMessageError(self::MESSAGE_ERROR)
                ->setArrayError($violations));
        }
    }

    /**
     * @param ObjectCoreInterface|null $objectCore
     * @param int $statusCode
     */
    public function checkCoreErrors(?ObjectCoreInterface $objectCore,
                                    int $statusCode = StatusHttp::UNPROCESSABLE_ENTITY): void
    {
        if (empty($objectCore)) {
            $exceptionCore = new ExceptionCore((new Config())
                ->setStatusCode($statusCode)
                ->setInternalMessageError(self::MESSAGE_ERROR));
            $this->addError($exceptionCore);
        }

        $violations = Violations::getViolations($this->validator, $objectCore);

        if ($this->hasErrors($violations)) {
            $exceptionCore = new ExceptionCore((new Config())
                ->setStatusCode($statusCode)
                ->setMessageError(self::MESSAGE_ERROR)
                ->setArrayError($violations));
            $this->addAllErrors($exceptionCore->getCustomError());
        }
    }

    /**
     * @param array $errors
     * @return bool
     */
    public function hasErrors(array $errors): bool
    {
        return (count($errors) > 0);
    }

    /**
     * @param array $errors
     */
    public function addAllErrors(array $errors): void
    {
        $this->errors = array_merge($this->errors, $errors);
    }

    /**
     * @param ExceptionCore $error
     */
    public function addError(ExceptionCore $error): void
    {
        $this->errors[] = $error;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}