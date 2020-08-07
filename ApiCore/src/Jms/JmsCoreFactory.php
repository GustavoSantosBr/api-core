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

namespace ApiCore\Jms;

use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\Visitor\Factory\JsonDeserializationVisitorFactory;
use JMS\Serializer\Visitor\Factory\JsonSerializationVisitorFactory;

/**
 * Class JmsCoreFactory
 * @package ApiCore\Jms
 */
class JmsCoreFactory
{
    public const JSON = "json";

    /**
     * @var SerializerInterface
     */
    private $jms;

    public function __construct()
    {
        $this->jms = $this->buildJms();
    }

    /**
     * @return SerializerInterface
     */
    private function buildJms(): SerializerInterface
    {
        $serializationVisitorFactory = new JsonSerializationVisitorFactory();
        $serializationVisitorFactory->setOptions(JSON_UNESCAPED_UNICODE);

        $deserializationVisitorFactory = new JsonDeserializationVisitorFactory();
        $deserializationVisitorFactory->setOptions(JSON_UNESCAPED_UNICODE);

        return SerializerBuilder::create()
            ->setSerializationVisitor(self::JSON, $serializationVisitorFactory)
            ->setDeserializationVisitor(self::JSON, $deserializationVisitorFactory)
            ->setPropertyNamingStrategy(new SerializedNameAnnotationStrategy(new IdenticalPropertyNamingStrategy()))
            ->build();
    }

    /**
     * @param $data
     * @param SerializationContext|null $context
     * @return string
     */
    public function serializeDataToJson($data, ?SerializationContext $context = null): string
    {
        return $this->jms->serialize($data, self::JSON, $context);
    }

    /**
     * @param $data
     * @param string $class
     * @param string $format
     * @return mixed
     */
    public function deserializeData($data, string $class, string $format = self::JSON)
    {
        return $this->jms->deserialize($data, $class, $format);
    }
}