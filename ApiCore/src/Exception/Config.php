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

/**
 * Class Config
 * @package ApiCore\Exception
 */
class Config
{
    /**
     * @var int|null
     */
    private $statusCode, $internalCode;

    /**
     * @var string|null
     */
    private $messageError, $internalMessageError, $traceError;

    /**
     * @var array|null
     */
    private $arrayError;

    /**
     * @var bool
     */
    private $repeatMessage = false;

    /**
     * @return int|null
     */
    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    /**
     * @param int|null $statusCode
     * @return $this
     */
    public function setStatusCode(?int $statusCode): Config
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getInternalCode(): ?int
    {
        return $this->internalCode;
    }

    /**
     * @param int|null $internalCode
     * @return Config
     */
    public function setInternalCode(?int $internalCode): Config
    {
        $this->internalCode = $internalCode;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMessageError(): ?string
    {
        return $this->messageError;
    }

    /**
     * @param string|null $messageError
     * @return Config
     */
    public function setMessageError(?string $messageError): Config
    {
        $this->messageError = $messageError;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getInternalMessageError(): ?string
    {
        return $this->internalMessageError;
    }

    /**
     * @param string|null $internalMessageError
     * @return Config
     */
    public function setInternalMessageError(?string $internalMessageError): Config
    {
        $this->internalMessageError = $internalMessageError;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTraceError(): ?string
    {
        return $this->traceError;
    }

    /**
     * @param string|null $traceError
     * @return Config
     */
    public function setTraceError(?string $traceError): Config
    {
        $this->traceError = $traceError;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getArrayError(): ?array
    {
        return $this->arrayError;
    }

    /**
     * @param array|null $arrayError
     * @return Config
     */
    public function setArrayError(?array $arrayError): Config
    {
        $this->arrayError = $arrayError;
        return $this;
    }

    /**
     * @return bool
     */
    public function getRepeatMessage(): bool
    {
        return $this->repeatMessage;
    }

    /**
     * @param bool $repeatMessage
     * @return Config
     */
    public function setRepeatMessage(bool $repeatMessage): Config
    {
        $this->repeatMessage = $repeatMessage;
        return $this;
    }
}