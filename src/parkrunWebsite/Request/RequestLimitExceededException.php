<?php

declare(strict_types=1);

namespace parkrunScraper\parkrunWebsite\Request;

class RequestLimitExceededException extends \Exception implements RateLimitExceptionInterface
{
}
