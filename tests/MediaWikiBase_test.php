<?php declare(strict_types=1);
use App\Helpers\MediaWikiBase;
use PHPUnit\Framework\TestCase;
use App\Helpers\Builder;

final class MediaWikiBaseTest extends TestCase
{
    /**
    * @beforeClass
    */
   public static function setUpSomeSharedFixtures(): void
   {
    /*
    $fn = '/tmp/unittest/testview';
       if (! is_dir($fn)){
        mkdir($fn, 0o777, true);
       }
    */
   }

    public function testWikiInternalLink(): void
    {
        $builder = new MediaWikiBase();
        $contents = '[[Kapitel Überblick|Überblick]]';
        $wiki = new MediaWikiBase();
                $html = $wiki->toHtml($contents);
        $this->assertEquals(
            '?',
            $html
        );
    }
}
