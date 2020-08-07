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

use ApiCore\DTO\Error;
use ApiCore\DTO\JsonResponse;
use ApiCore\Hateoas\HateoasCore;
use ApiCore\Jms\JmsCoreFactory;
use ApiCore\LoadConfigData;
use Exception;
use Http\StatusHttp;
use JMS\Serializer\SerializationContext;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\InjectContentTypeTrait;
use Laminas\Diactoros\Stream;
use Throwable;

/**
 * Class JsonResponseCore
 * @package ApiCore\Response
 */
final class JsonResponseCore extends Response
{
    use InjectContentTypeTrait;

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var int
     */
    private $statusCode, $defaultCode;

    /**
     * @var bool
     */
    private $serializeNull;

    /**
     * @var string
     */
    private $defaultMessage, $defaultBaseUri;

    public function __construct($data, ?int $statusCode, bool $serializeNull = false)
    {
        $this->config();
        $this->data = $data;
        $this->serializeNull = $serializeNull;
        $this->statusCode = $this->createStatusCode($statusCode);
        $contentType = $this->injectContentType("application/json", []);
        parent::__construct($this->createBody(), $this->statusCode, $contentType);
    }

    private function config(): void
    {
        $apiCore = [];

        try {
            $apiCore = LoadConfigData::getConfig("api_core") ?? null;
        } catch (Exception $e) {
            # do nothing
        }

        $this->defaultCode = $apiCore["default_code_error"] ?? -1;
        $this->defaultMessage = $apiCore["default_message_error"] ?? "Ocorreu um erro inesperado na aplicação!";
        $this->defaultBaseUri = $apiCore["default_base_uri"] ?? null;
    }

    /**
     * @param int|null $statusCode
     * @return int
     */
    private function createStatusCode(?int $statusCode): int
    {
        return (empty($statusCode) || $statusCode < 0) ? StatusHttp::INTERNAL_SERVER_ERROR : $statusCode;
    }

    /**
     * @return Stream
     * @throws Exception
     */
    private function createBody(): Stream
    {
        $jms = new JmsCoreFactory();
        $serializationContext = (new SerializationContext())->setSerializeNull($this->serializeNull);

        $body = new Stream("php://temp", "wb+");
        $body->write($jms->serializeDataToJson($this->createResponse(), $serializationContext));
        $body->rewind();
        return $body;
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    private function createResponse(): JsonResponse
    {
        $response = new JsonResponse();
        $response->setStatusCode($this->statusCode);

        if (TypeResponse::getType($this->statusCode)) {
            $response->setData($this->data);
            $response->setLinks((new HateoasCore($this->data, $this->defaultBaseUri))->createHateoas());
            return $response;
        }

        if (is_array($this->data)) {
            $response->setError($this->data);
            return $response;
        }

        $error = new Error();
        $error->setCode($this->defaultCode);
        $error->setMessageError($this->defaultMessage);

        if (is_string($this->data)) {
            $error->setTraceError($this->data);
        }

        if ($this->data instanceof Throwable) {
            $error->setTraceError($this->data->getMessage());
        }

        $response->setError([$error]);
        return $response;
    }
}