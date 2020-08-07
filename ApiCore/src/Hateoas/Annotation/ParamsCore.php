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

namespace ApiCore\Hateoas\Annotation;

use Doctrine\Common\Annotations\Annotation\Enum;

/**
 * @Annotation
 * @Target("ANNOTATION")
 */
class ParamsCore
{
    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $rel;

    /**
     * @var string
     * @Required
     * @Enum(value={"GET", "POST", "PUT", "PATCH", "DELETE", "OPTIONS", "CONNECT", "TRACE", "HEAD"})
     */
    public $method;

    /**
     * @var string
     * @Required
     */
    public $href;

    /**
     * @var array
     */
    public $params;

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @return string|null
     */
    public function getRel(): string
    {
        return $this->rel;
    }

    /**
     * @return string|null
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string|null
     */
    public function getHref(): ?string
    {
        return $this->href;
    }

    /**
     * @return array|null
     */
    public function getParams(): ?array
    {
        return $this->params;
    }
}