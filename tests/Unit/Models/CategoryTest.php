<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Traits\Uuid;
use \Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{

    private $category;

    protected function setUp() : void
    {
        parent::setUp();

        $this->category = new Category();
    }

    public function testFillableAttributes()
    {

        $fillable = ['name', 'description', 'is_active'];

        $this->assertEquals($fillable, $this->category->getFillable() );
    }

    public function testIfUseTraits()
    {
        $traits = [SoftDeletes::class, Uuid::class];
        $categoryTraits = array_keys(class_uses(Category::class));

        $this->assertEquals($traits, $categoryTraits);
    }

    public function testIfCastsAttributes()
    {
        $casts = ['id' => 'string', 'is_active' => 'boolean'];

        $this->assertEquals($casts, $this->category->getCasts());
    }

    public function testIfIncrementingAttributes()
    {
        $this->assertFalse($this->category->incrementing);
    }

    public function testIfDatesAttributes()
    {
        $dates = ['updated_at', 'created_at', 'deleted_at'];

        foreach ($dates as $date) {
            $this->assertContains($date, $this->category->getDates());
        }

        $this->assertCount(count($dates), $this->category->getDates());
    }
}
