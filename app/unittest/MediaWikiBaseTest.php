<?php
namespace Unittest;

use App\Helpers\MediaWikiBase;
use Unittest\UnitTest;

class MediaWikiBaseTest extends UnitTest{
    public function __construct(){
        echo "= MediaWikiBaseTest =\n";
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
    public function run(): void
    {
         $this->testWikiInternalLink();
    }
}