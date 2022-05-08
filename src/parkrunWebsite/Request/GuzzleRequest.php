<?php

declare(strict_types=1);

namespace parkrunScraper\parkrunWebsite\Request;

/**
 * An implementation of a website request implemented with Guzzle.
 */
class GuzzleRequest implements RequestInterface
{
    /**
     * @todo Separate Dependencies with DI.
     * @todo Seperate Logic for Caching into cached implementation of Interface.
     * @todo Seperate Request Log functionality.
     */
    public function makeRequest(string $location, string $event): string|\Stringable
    {
        $url = "https://www.parkrun.org.uk/{$location}/results/{$event}/";

        // Load Cached version.
        // Need to add file based timeout.
        // @todo Consider ROOT_DIR const.
        if (file_exists(__DIR__ . '/../../../tmp/cache/' . md5($url))) {
            return (string) file_get_contents(__DIR__ . '/../../../tmp/cache/' . md5($url));
        }

        // Make Request
        $client = new \GuzzleHttp\Client();
        $faker = \Faker\Factory::create();
        $jar = new \GuzzleHttp\Cookie\FileCookieJar(__DIR__ . '/../../../tmp/cookie');
        $res = $client->request(
            'GET',
            $url,
            [
                'cookies' => $jar,
                'headers' => [
                    'User-Agent' => $faker->userAgent(),
                ],
            ]
        );

        // Append to RequestLog.
        $logFile = __DIR__ . '/../../../tmp/request-log.php';
        $log = [];
        if (file_exists($logFile)) {
            $log = require $logFile;
        }
        $log[] = ['url' => $url, 'timestamp' => time()];
        file_put_contents(
            $logFile,
            "<?php\nreturn " . var_export($log, true) . ";"
        );

        if ($res->getStatusCode() !== 200) {
            // @todo Handle errors properly.
            // @todo Custom Exception.
            echo $res->getStatusCode();
            echo "\n\n";

            echo $res->getHeader('content-type')[0];
            echo "\n\n";

            echo substr((string) $res->getBody(), 0, 200);
            throw new \Exception('Unable to read');
        }

        // Log to Cache.
        file_put_contents(__DIR__ . '/../../../tmp/cache/' . md5($url), $res->getBody());

        return $res->getBody();
    }
}
