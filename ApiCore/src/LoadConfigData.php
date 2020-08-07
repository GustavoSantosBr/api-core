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

namespace ApiCore;

use Psr\Container\ContainerInterface;

/**
 * Class LoadConfigData
 * @package ApiCore
 */
abstract class LoadConfigData
{
    /**
     * @var ContainerInterface
     */
    private static $container;

    /**
     * @return ContainerInterface
     */
    public static function getContainer(): ContainerInterface
    {
        return self::$container;
    }

    /**
     * @param ContainerInterface $container
     */
    public static function setContainer(ContainerInterface $container): void
    {
        self::$container = $container;
    }

    /**
     * @param mixed $configKeysArray
     * @return mixed
     */
    public static function getConfig(...$configKeysArray)
    {
        if (self::$container === null) {
            return null;
        }

        $keys = null;
        $config = self::$container->get("config");

        foreach ($configKeysArray as $index => $value) {
            $keys = ($index === 0 ? $config[$value] : $keys[$value]);
        }
        return $keys;
    }
}