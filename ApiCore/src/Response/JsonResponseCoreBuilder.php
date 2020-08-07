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

namespace ApiCore\Response;

use Exception;

/**
 * Class JsonResponseCoreBuilder
 * @package ApiCore\Response
 */
final class JsonResponseCoreBuilder
{
    /**
     * @var mixed
     */
    private $data;

    /**
     * @var int|null
     */
    private $statusCode;

    /**
     * @var bool
     */
    private $serializeNull = false;

    /**
     * @return JsonResponseCore
     * @throws Exception
     */
    public function build(): JsonResponseCore
    {
        return new JsonResponseCore($this->data, $this->statusCode, $this->serializeNull);
    }

    /**
     * @param mixed $data
     * @return JsonResponseCoreBuilder
     */
    public function setData($data = null): JsonResponseCoreBuilder
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param int|null $statusCode
     * @return JsonResponseCoreBuilder
     */
    public function setStatusCode(?int $statusCode): JsonResponseCoreBuilder
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param bool $serializeNull
     * @return JsonResponseCoreBuilder
     */
    public function setSerializeNull(bool $serializeNull): JsonResponseCoreBuilder
    {
        $this->serializeNull = $serializeNull;
        return $this;
    }
}