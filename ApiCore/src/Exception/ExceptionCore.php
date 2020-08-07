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

namespace ApiCore\Exception;

use ApiCore\DTO\Error;
use Exception;
use Http\StatusHttp;

/**
 * Class ExceptionCore
 * @package ApiCore\Exception
 */
class ExceptionCore extends Exception
{
    /**
     * @var int
     */
    private $statusCode, $internalCode;

    /**
     * @var string
     */
    private $messageError, $internalMessageError, $traceError;

    /**
     * @var array
     */
    private $arrayError, $customArrayError;

    /**
     * @var bool
     */
    private $repeatMessage;

    public function __construct(Config $configExceptionCore)
    {
        $this->statusCode = $configExceptionCore->getStatusCode() ?? StatusHttp::INTERNAL_SERVER_ERROR;
        $this->internalCode = $configExceptionCore->getInternalCode();
        $this->messageError = $configExceptionCore->getMessageError();
        $this->internalMessageError = $configExceptionCore->getInternalMessageError();
        $this->traceError = $configExceptionCore->getTraceError();
        $this->arrayError = $configExceptionCore->getArrayError();
        $this->repeatMessage = $configExceptionCore->getRepeatMessage();
        parent::__construct($this->messageError ?? "", $this->statusCode);
        $this->createCustomArrayError();
    }

    private function createCustomArrayError(): void
    {
        $countError = ((!empty($this->arrayError)) ? count($this->arrayError) : 0);
        $this->customArrayError = [];

        if ($countError <= 0) {
            array_push($this->customArrayError, $this->createCustomError($this->messageError));
            return;
        }

        foreach ($this->arrayError as $errorData) {
            if (!is_array($errorData)) {
                array_push($this->customArrayError, $this->createCustomError($errorData));
                continue;
            }

            foreach ($errorData as $messageError) {
                array_push($this->customArrayError, $messageError);
            }
        }
    }

    /**
     * @param $errorData
     * @return Error
     */
    private function createCustomError($errorData): Error
    {
        $internalCode = $this->internalCode;
        $messageError = $this->messageError;
        $internalMessageError = $this->internalMessageError;
        $traceError = $this->traceError;

        $errorResponse = new Error();

        if ($errorData instanceof Error) {
            $internalCode = $internalCode ?: $errorData->getCode();
            $messageError = $messageError ?: $errorData->getMessageError();
            $internalMessageError = $errorData->getInternalMessageError();
            $traceError = $errorData->getTraceError();
        }

        if ((is_string($errorData)) || ($errorData === null)) {
            $messageError = $errorData;
        }

        $errorResponse->setCode($internalCode);
        $errorResponse->setMessageError($messageError);
        $errorResponse->setInternalMessageError($internalMessageError);
        $errorResponse->setTraceError($traceError);

        if ((!$this->repeatMessage) &&
            ($errorResponse->getMessageError() === $errorResponse->getInternalMessageError())) {
            $errorResponse->setInternalMessageError(null);
        }
        return $errorResponse;
    }

    /**
     * @return int|null
     */
    public function getInternalCode(): ?int
    {
        return $this->internalCode;
    }

    /**
     * @return string|null
     */
    public function getMessageError(): ?string
    {
        return $this->messageError;
    }

    /**
     * @return string|null
     */
    public function getInternalMessageError(): ?string
    {
        return $this->internalMessageError;
    }

    /**
     * @return string|null
     */
    public function getTraceError(): ?string
    {
        return $this->traceError;
    }

    /**
     * @return array
     */
    public function getCustomError(): array
    {
        return $this->customArrayError;
    }
}