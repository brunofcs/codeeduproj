<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GenreControllerTest extends TestCase
{

    use DatabaseMigrations, TestValidations, TestSaves;

    private $genre;

    protected function setUp(): void
    {
        parent::setUp();

        $this->genre = factory(Genre::class)->create();
    }

    public function testIndex()
    {
        $response = $this->get(route('genres.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$this->genre->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('genres.show', ['genre' => $this->genre->id]));

        $response
            ->assertStatus(200)
            ->assertJson($this->genre->toArray());
    }

    public function testInvalidationData()
    {

        $data = [
            'name' => ''
        ];
        $this->assertInvalidationStoreAction($data, 'required');
        $this->assertInvalidationUpdateAction($data, 'required');

        $data = [
            'name' => str_repeat('a', 256),
        ];
        $this->assertInvalidationStoreAction($data, 'max.string', ['max'=>255]);
        $this->assertInvalidationUpdateAction($data, 'max.string', ['max'=>255]);

        $data = [
            'is_active' => 'a'
        ];
        $this->assertInvalidationStoreAction($data, 'boolean');
        $this->assertInvalidationUpdateAction($data, 'boolean');

    }

    public function testStore()
    {
        $data = [
            'name'=>'test'
        ];
        $this->assertStore($data, $data + ['is_active'=>true, 'deleted_at'=>null]);

        $data = [
            'name' => 'test',
            'is_active' => false
        ];
        $this->assertStore($data, $data + ['is_active'=>false]);
    }

    public function testUpdate()
    {
        $this->genre = factory(Genre::class)->create([
            'is_active'   => false
        ]);

        $data = [
            'name' => 'test',
            'is_active' => true
        ];
        $response = $this->assertUpdate($data, $data + ['deleted_at'=>null]);
        $response->assertJsonStructure([
            'created_at', 'updated_at'
        ]);

    }

    public function testDelete()
    {
        $response = $this->json('DELETE', route('genres.destroy', ['genre' => $this->genre->id]));

        $response->assertStatus(204);

        $this->assertNull(Genre::find($this->genre->id));
        $this->assertNotNull(Genre::withTrashed()->find($this->genre->id));
    }

    protected function routeStore()
    {
        return route('genres.store');
    }

    protected function routeUpdate()
    {
        return route('genres.update', ['genre'=>$this->genre->id]);
    }

    protected function model()
    {
        return Genre::class;
    }
}
