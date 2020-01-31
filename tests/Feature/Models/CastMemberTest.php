<?php

namespace Tests\Feature\Models;

use App\Models\CastMember;
use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CastMemberTest extends TestCase
{
    use DatabaseMigrations;

    public function testList()
    {
        // Criando CastMember usando o Seeder
        factory(CastMember::class, 1)->create();

        $castMembers     = CastMember::all();
        $castMembersKeys = array_keys($castMembers->first()->getAttributes());

        $this->assertCount(1, $castMembers);
        $this->assertEqualsCanonicalizing([
            "id",
            "name",
            "type",
            "created_at",
            "updated_at",
            "deleted_at"
        ], $castMembersKeys);
    }

    public function testCreate()
    {
        $castMember  = CastMember::create([
            'name' => 'Cast Test1',
            'type' => CastMember::TYPE_DIRECTOR
        ]);
        $castMember->refresh();

        $this->assertEquals('Cast Test1', $castMember->name);
        $this->assertEquals(CastMember::TYPE_DIRECTOR, $castMember->type);

        $castMember  = CastMember::create([
            'name' => 'Cast Test1',
            'type' => CastMember::TYPE_ACTOR
        ]);

        $this->assertEquals(CastMember::TYPE_ACTOR, $castMember->type);
    }

    public function testUpdate()
    {
        // Criando CastMember usando o Seeder
        $castMember = factory(CastMember::class)->create([
            'name' => 'Cast 1',
            'type'   => CastMember::TYPE_DIRECTOR
        ])->first();

        $data = [
            'name'        => 'new name',
            'type'   => CastMember::TYPE_ACTOR
        ];
        $castMember->update($data);

        foreach ($data as $key => $value) {
            $this->assertEquals($value, $castMember->{$key});
        }

    }

    public function testDelete()
    {
        // Criando CastMember usando o Seeder
        $castMember = factory(CastMember::class, 1)->create()->first();

        $id = $castMember->id;

        $this->assertTrue($castMember->delete());
        $this->assertNull(CastMember::find($id));

    }

    public function testUUID()
    {
        // Criando CastMember usando o Seeder
        $castMember = factory(CastMember::class, 1)->create()->first();

        $uuidBlockLen = [8, 4, 4, 4, 12];

        $uuidBlocks = explode('-', $castMember->id);

        $this->assertCount(5, $uuidBlocks);

        foreach ($uuidBlocks as $key => $value) {
            $this->assertEquals($uuidBlockLen[$key], strlen($value));
        }
    }
}
