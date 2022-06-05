<?php

declare(strict_types=1);

namespace parkrunScraper\parkrunWebsite\Request;

class RequestGracePeriodExceededException extends \Exception implements RateLimitExceptionInterface
{
}
