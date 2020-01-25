<?php

namespace Tests\Traits;


use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\Lang;

trait TestSaves {

    protected abstract function routeStore();
    protected abstract function routeUpdate();
    protected abstract function model();

    protected function assertStore(array $sendData, array $testDatabaseData, array $testJsonData = null) : TestResponse
    {
        /** @var TestResponse $response */
        $response = $this->json('POST', $this->routeStore(), $sendData);
        if($response->status() !== 201) {
            throw new \Exception("Response status must be 201, given {$response->status()}:\n {$response->content()}");
        }

        $this->assertInDatabase($response, $testDatabaseData);
        $this->assertJsonResponseContent($response, $testDatabaseData, $testJsonData);

        return $response;
    }

    protected function assertUpdate(array $sendData, array $testDatabaseData, array $testJsonData = null) : TestResponse
    {
        /** @var TestResponse $response */
        $response = $this->json('PUT', $this->routeUpdate(), $sendData);
        if($response->status() !== 200) {
            throw new \Exception("Response status must be 201, given {$response->status()}:\n {$response->content()}");
        }

        $this->assertInDatabase($response, $testDatabaseData);
        $this->assertJsonResponseContent($response, $testDatabaseData, $testJsonData);

        return $response;
    }

    private function assertInDatabase(TestResponse $response, array $testDatabase)
    {
        $model = $this->model();
        $table = (new $model)->getTable();
        $this->assertDatabaseHas($table, $testDatabase + ['id'=>$response->json('id')]);
    }

    private function assertJsonResponseContent(TestResponse $response, array $testDatabaseData, array $testJsonData = null)
    {
        $testResponse = $testJsonData ?? $testDatabaseData;
        $response->assertJsonFragment($testResponse + ['id'=>$response->json('id')]);
    }


}
