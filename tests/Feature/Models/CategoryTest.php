<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        // criando Category Manualmente
        // $category  = Category::create([
        //     'name' => 'Category Test1'
        // ]);

        // Criando Category usando o Seeder
        factory(Category::class, 1)->create();

        $categories     = Category::all();
        $categoriesKeys = array_keys($categories->first()->getAttributes());

        $this->assertCount(1, $categories);
        $this->assertEqualsCanonicalizing([
            "id",
            "name",
            "description",
            "is_active",
            "created_at",
            "updated_at",
            "deleted_at"
        ], $categoriesKeys);
    }

    public function testCreate()
    {
        $category  = Category::create([
            'name' => 'Category Test1'
        ]);
        $category->refresh();

        $this->assertEquals('Category Test1', $category->name);
        $this->assertNull($category->description);
        $this->assertTrue($category->is_active);

        $category  = Category::create([
            'name' => 'Category Test1',
            'description' => 'description1'
        ]);

        $this->assertEquals('description1', $category->description);

        $category  = Category::create([
            'name' => 'Category Test1',
            'is_active' => false
        ]);
        $this->assertFalse($category->is_active);
    }

    public function testUpdate()
    {
        // Criando Category usando o Seeder
        $category = factory(Category::class)->create([
            'description' => 'testedescription',
            'is_active'   => false
        ])->first();

        $data = [
            'name'        => 'new name',
            'description' => 'test_description',
            'is_active'   => true
        ];
        $category->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $category->{$key});
        }

    }

    public function testDelete()
    {
        // Criando Category usando o Seeder
        $category = factory(Category::class, 1)->create()->first();

        $id = $category->id;

        $this->assertTrue($category->delete());
        $this->assertNull(Category::find($id));

    }

    public function testUUID()
    {
        // Criando Category usando o Seeder
        $category = factory(Category::class, 1)->create()->first();

        $uuidBlockLen = [8, 4, 4, 4, 12];

        $uuidBlocks = explode('-', $category->id);

        $this->assertCount(5, $uuidBlocks);

        foreach ($uuidBlocks as $key => $value) {
            $this->assertEquals($uuidBlockLen[$key], strlen($value));
        }
    }
}
