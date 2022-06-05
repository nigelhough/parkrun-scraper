<?php

declare(strict_types=1);

namespace parkrunScraper\parkrunWebsite\Parser;

use parkrunScraper\Athlete\Athlete;
use parkrunScraper\Event\Event;
use parkrunScraper\Event\EventSummary;
use parkrunScraper\EventInterface;
use parkrunScraper\EventSummaryInterface;
use parkrunScraper\Location\Location;
use parkrunScraper\Result\NoResult;
use parkrunScraper\Result\Result;
use parkrunScraper\Result\ResultCollection;
use parkrunScraper\Result\Time\FormattedTime;

/**
 * Use a \DOMDocument and \DOMXpath queries to parse the parkrun website response.
 */
class DomDocumentParser implements ParserInterface
{
    public function parseEvent(string $pageBody): EventInterface
    {
        $document = new \DOMDocument();
        $document->loadHTML($pageBody, LIBXML_NOWARNING | LIBXML_NOERROR);

        return $this->eventFromDocument($document);
    }

    public function parseEventSummary(string $pageBody): EventSummaryInterface
    {
        $document = new \DOMDocument();
        $document->loadHTML($pageBody, LIBXML_NOWARNING | LIBXML_NOERROR);

        $event = $this->eventFromDocument($document);

        $xpath = new \DOMXpath($document);

        $rows = $xpath->query(
            "//tbody[contains(@class, 'js-ResultsTbody')]//tr[contains(@class, 'Results-table-row')]"
        );
        $lastRow = $xpath->query(
            "//tbody[contains(@class, 'js-ResultsTbody')]//tr[contains(@class, 'Results-table-row')][last()]"
        );

        if ($rows === false || $lastRow === false) {
            throw new ParserException('Unable to parse website response');
        }

        $totalRunners = (int) $lastRow[0]->getAttribute('data-position');
        $results = new ResultCollection(
            $totalRunners,
            (function (\DOMNodeList $rows) use ($xpath, $event): \Generator {
                /** @var \DOMElement $row */
                foreach ($rows as $row) {
                    $name = $row->getAttribute('data-name');
                    $position = $row->getAttribute('data-position');
                    $isUnknown = ($name === 'Unknown');

                    if ($isUnknown) {
                        yield new NoResult($event, (int) $position);
                        continue;
                    }

                    $ageGroup = $row->getAttribute('data-agegroup');
                    $club = $row->getAttribute('data-club');
                    $gender = $row->getAttribute('data-gender');
                    $runs = $row->getAttribute('data-runs');
                    $volunteers = $row->getAttribute('data-vols');
                    $ageGrade = $row->getAttribute('data-agegrade');
                    $achievement = $row->getAttribute('data-achievement');
                    $link = (string) $this->getXPathElement($xpath, "//a", $row)?->getAttribute('href');
                    $athleteId = basename($link);
                    $time = (string) $this->getXPathValue(
                        $xpath,
                        "//td[contains(@class, 'Results-table-td Results-table-td--time')]//div[1]",
                        $row
                    );
                    $pb = (string) $this->getXPathValue(
                        $xpath,
                        "//td[contains(@class, 'Results-table-td Results-table-td--time')]//div[2]",
                        $row
                    );
                    $time = new FormattedTime($time);
                    /** @todo use Bitwise or enum */
                    $pb = $achievement === 'New PB!' ? $time : new FormattedTime(
                        trim($pb, " PB\xC2\xA0")
                    );

                    yield new Result(
                        new Athlete((int) $athleteId, $name, (int) $runs, (int) $volunteers, $club, $gender),
                        $event,
                        (int) $position,
                        $time,
                        $pb,
                        $ageGroup,
                        (float) $ageGrade,
                        $achievement
                    );
                }
            })(
                $rows
            )
        );

        return new EventSummary(
            $event,
            $results
        );
    }

    private function eventFromDocument(\DOMDocument $document): EventInterface
    {
        $xpath = new \DOMXpath($document);

        $location = $this->getXPathValue($xpath, "//div[contains(@class, 'Results-header')]//h1");
        $date = $this->getXPathValue(
            $xpath,
            "//div[contains(@class, 'Results-header')]//h3//span[contains(@class, 'format-date')]"
        );
        $eventNo = $this->getXPathValue(
            $xpath,
            "//div[contains(@class, 'Results-header')]//h3//span[starts-with(text(),'#')]"
        );

        if ($location === null || $eventNo === null || $date === null) {
            throw new ParserException('Unable to parse website response');
        }
        $date = \DateTimeImmutable::createFromFormat("d/m/Y H:i:s", $date . " 00:00:00");
        if ($date === false) {
            throw new ParserException('Unable to parse website response');
        }

        return new Event(
            new Location($location),
            (int) trim($eventNo, '#'),
            $date
        );
    }

    private function getXPathValue(\DOMXPath $xpath, string $query, ?\DOMElement $context = null): ?string
    {
        return $this->getXPathElement($xpath, $query, $context)?->nodeValue;
    }

    private function getXPathElement(\DOMXPath $xpath, string $query, ?\DOMElement $context = null): ?\DOMElement
    {
        // The Context element passed to query wasn't taking effect so building the full XPath was the solution.
        $query = ($context ? $context->getNodePath() : '') . $query;
        if (!$elements = $xpath->query($query, $context)) {
            return null;
        }
        if (!$element = $elements[0]) {
            return null;
        }

        return $element;
    }
}
