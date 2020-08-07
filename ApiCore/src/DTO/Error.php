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

namespace ApiCore\DTO;

use JMS\Serializer\Annotation\Type;

/**
 * Class Error
 * @package ApiCore\DTO
 */
class Error
{
    /**
     * @var int|null
     * @Type("int")
     */
    private $code;

    /**
     * @var string|null
     * @Type("string")
     */
    private $message_error;

    /**
     * @var string|null
     * @Type("string")
     */
    private $internal_message_error;

    /**
     * @var string|null
     * @Type("string")
     */
    private $trace_error;

    /**
     * @return int|null
     */
    public function getCode(): ?int
    {
        return $this->code;
    }

    /**
     * @param int|null $code
     */
    public function setCode(?int $code): void
    {
        $this->code = $code;
    }

    /**
     * @return string|null
     */
    public function getMessageError(): ?string
    {
        return $this->message_error;
    }

    /**
     * @param string|null $message_error
     */
    public function setMessageError(?string $message_error): void
    {
        $this->message_error = $message_error;
    }

    /**
     * @return string|null
     */
    public function getInternalMessageError(): ?string
    {
        return $this->internal_message_error;
    }

    /**
     * @param string|null $internal_message_error
     */
    public function setInternalMessageError(?string $internal_message_error): void
    {
        $this->internal_message_error = $internal_message_error;
    }

    /**
     * @return string|null
     */
    public function getTraceError(): ?string
    {
        return $this->trace_error;
    }

    /**
     * @param string|null $trace_error
     */
    public function setTraceError(?string $trace_error): void
    {
        $this->trace_error = $trace_error;
    }
}