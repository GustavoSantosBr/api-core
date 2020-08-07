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

namespace ApiCore\Hateoas;

use ApiCore\Hateoas\Annotation\ParamsCore;
use ApiCore\Hateoas\Annotation\RestCore;
use ApiCore\Hateoas\Annotation\ValueCore;
use Doctrine\Common\Annotations\AnnotationReader;
use Exception;
use Http\StatusHttp;
use ReflectionClass;
use ReflectionException;

/**
 * Class HateoasCore
 * @package ApiCore\Hateoas
 */
final class HateoasCore
{
    /**
     * @var mixed
     */
    private $data;

    /**
     * @var string|null
     */
    private $uri;

    public function __construct($data, ?string $uri)
    {
        $this->data = $data;
        $this->uri = $uri;
    }

    /**
     * @return ParamsCore[]|array|null
     * @throws Exception
     */
    public function createHateoas(): ?array
    {
        try {
            if (!is_object($this->data)) {
                return null;
            }

            $class = get_class($this->data);

            if (!class_exists($class)) {
                return null;
            }

            $reflectionClass = new ReflectionClass($class);
            $reader = new AnnotationReader();

            /** @var RestCore $restAnnotation */
            $restAnnotation = $reader->getClassAnnotation($reflectionClass, RestCore::class);

            if (empty($restAnnotation)) {
                return null;
            }

            /** @var ParamsCore[] $restArray */
            $restArray = [];

            foreach ($restAnnotation->params as $paramsCore) {
                /** @var ParamsCore $paramsCore */
                $href = $paramsCore->getHref();
                $baseUri = null;

                if (!empty($this->uri)) {
                    $baseUri = "{$this->uri}{$href}";
                }

                $baseUri = $baseUri ?? $href;
                $paramsCoreArray = $paramsCore->getParams();

                if (empty($paramsCoreArray)) {
                    $paramsCore->href = $baseUri;
                    array_push($restArray, $paramsCore);
                    continue;
                }

                foreach ($paramsCoreArray as $param) {
                    /** @var ValueCore $param */
                    $key = $param->getKey();
                    $baseUri = $this->addBaseUriParams($reflectionClass, $key, $baseUri);
                }

                $paramsCore->href = $baseUri;
                $paramsCore->params = null;
                array_push($restArray, $paramsCore);
            }
            return $restArray;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), StatusHttp::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param ReflectionClass $reflectionClass
     * @param string $key
     * @param string $baseUri
     * @return string
     * @throws Exception
     */
    private function addBaseUriParams(ReflectionClass $reflectionClass, string $key, string $baseUri): string
    {
        try {
            $reflectionProperty = $reflectionClass->getProperty($key);
            $reflectionProperty->setAccessible(true);
            return str_replace($key, $reflectionProperty->getValue($this->data), $baseUri);
        } catch (ReflectionException $e) {
            throw new Exception($e->getMessage(), StatusHttp::INTERNAL_SERVER_ERROR);
        }
    }
}