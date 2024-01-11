<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use App\Helpers\Builder;

final class BuilderTest extends TestCase
{
    /**
    * @beforeClass
    */
   public static function setUpSomeSharedFixtures(): void
   {
    $fn = '/tmp/unittest/testview';
       if (! is_dir($fn)){
        mkdir($fn, 0o777, true);
       }
   }

    public function testCreateModule(): void
    {
        $builder = new Builder();
        $builder->readDefinition('data/builder.input.php');
        $templates = 'templates/builder';
        $controllers = '/tmp/unittest';
        $models = '/tmp/unittest';
        $views = '/tmp/unittest';
        $builder->createModule($templates, $views, $controllers, $models);
    }
}
