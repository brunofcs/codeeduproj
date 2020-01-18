<?php

namespace Tests\Feature\Models;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenreTest extends TestCase
{

    use DatabaseMigrations;

    public function testList()
    {
        // criando Genre Manualmente
        // $genre  = Genre::create([
        //     'name' => 'Genre Test1'
        // ]);

        // Criando Genre usando o Seeder
        factory(Genre::class, 1)->create();

        $genres     = Genre::all();
        $genresKeys = array_keys($genres->first()->getAttributes());

        $this->assertCount(1, $genres);
        $this->assertEqualsCanonicalizing([
            "id",
            "name",
            "is_active",
            "created_at",
            "updated_at",
            "deleted_at"
        ], $genresKeys);
    }

    public function testCreate()
    {
        $genre  = Genre::create([
            'name' => 'Genre Test1'
        ]);
        $genre->refresh();

        $this->assertEquals('Genre Test1', $genre->name);
        $this->assertTrue($genre->is_active);

        $genre  = Genre::create([
            'name' => 'Genre Test1',
            'is_active' => false
        ]);
        $this->assertFalse($genre->is_active);
    }

    public function testUpdate()
    {
        // Criando Genre usando o Seeder
        $genre = factory(Genre::class)->create([
            'name' => 'testename',
            'is_active'   => false
        ])->first();

        $data = [
            'name'        => 'new name',
            'is_active'   => true
        ];
        $genre->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $genre->{$key});
        }

    }

    public function testDelete()
    {
        // Criando Genre usando o Seeder
        $genre = factory(Genre::class, 1)->create()->first();

        $id = $genre->id;

        $this->assertTrue($genre->delete());
        $this->assertNull(Genre::find($id));

        // testa a restauracao devido a exclusao logica
        $genre->restore();
        $this->assertNotNull(Genre::find($id));
    }

    public function testUUID()
    {
        // Criando Genre usando o Seeder
        $genre = factory(Genre::class, 1)->create()->first();

        $uuidBlockLen = [8, 4, 4, 4, 12];

        $uuidBlocks = explode('-', $genre->id);

        $this->assertCount(5, $uuidBlocks);

        foreach ($uuidBlocks as $key => $value) {
            $this->assertEquals($uuidBlockLen[$key], strlen($value));
        }
    }
}
