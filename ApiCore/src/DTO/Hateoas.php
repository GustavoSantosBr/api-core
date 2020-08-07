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
 * Class Hateoas
 * @package ApiCore\DTO
 */
class Hateoas
{
    /**
     * @var string|null
     * @Type(name="string")
     */
    private $name;

    /**
     * @var string|null
     * @Type(name="string")
     */
    private $href;

    /**
     * @var string|null
     * @Type(name="string")
     */
    private $method;

    public function __construct(?string $name, ?string $href, ?string $method)
    {
        $this->name = $name;
        $this->href = $href;
        $this->method = $method;
    }
}