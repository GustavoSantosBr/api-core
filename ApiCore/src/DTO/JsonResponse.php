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
 * Class JsonResponse
 * @package ApiCore\DTO
 */
class JsonResponse
{
    /**
     * @var int
     * @Type("int")
     */
    private $status_code;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var array|null
     */
    private $links;

    /**
     * @var mixed
     */
    private $error;

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->status_code;
    }

    /**
     * @param int $status_code
     */
    public function setStatusCode(int $status_code): void
    {
        $this->status_code = $status_code;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

    /**
     * @return array|null
     */
    public function getLinks(): ?array
    {
        return $this->links;
    }

    /**
     * @param array|null $links
     */
    public function setLinks(?array $links): void
    {
        $this->links = $links;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param mixed $error
     */
    public function setError($error): void
    {
        $this->error = $error;
    }
}