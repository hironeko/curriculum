<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Todo;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TodoControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp():void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function Todoの新規作成()
    {
        $params = [
            'title' => 'テスト:タイトル',
            'content' => 'テスト:内容'
        ];

        $res = $this->postJson(route('api.todo.create'), $params);
        $res->assertOk();
        $todos = Todo::all();

        $this->assertCount(1, $todos);

        $todo = $todos->first();

        $this->assertEquals($params['title'], $todo->title);
        $this->assertEquals($params['content'], $todo->content);

    }

    /**
     * @test
     */
    public function Todoの新規作成でValidationに引っかかる()
    {
        $params = [
            'title' => 'テスト:タイトル',
            'content' => null
        ];

        $res = $this->postJson(route('api.todo.create'), $params);
        $res->assertStatus(422);
    }

    /**
     * @test
     */
    public function Todoの詳細取得()
    {
        $todo = Todo::factory()->create();

        $res = $this->getJson(route('api.todo.show', $todo->id));

        $res->assertOk();

        $data = $res->json();

        $this->assertSame($todo->title, $data['title']);
        $this->assertSame($todo->content, $data['content']);
    }

    /**
     * @test
     */
    public function Todoの詳細取得でデータが存在しない()
    {
        $todo = Todo::factory()->create();

        $res = $this->getJson(route('api.todo.show', $todo->id + 1));

        $res->assertStatus(404);
    }

    /**
     * @test
     */
    public function Todoの更新()
    {
        $todo = Todo::factory()->create();

        $params = [
            'title' => '更新タイトル',
            'content' => '更新コンテント'
        ];

        $res = $this->putJson(route('api.todo.update', $todo->id), $params);

        $res->assertOk();

        $data = $res->json();
        $this->assertSame($params['title'], $data['title']);
        $this->assertSame($params['content'], $data['content']);
    }

    /**
     * @test
     */
    public function Todoの更新の際にvalidationに引っかかる()
    {
        $todo = Todo::factory()->create();

        $params = [
            'title' => '更新タイトル',
            'content' => null
        ];

        $res = $this->putJson(route('api.todo.update', $todo->id), $params);

        $res->assertStatus(422);
    }

    /**
     * @test
     */
    public function Todoの更新の際に対象が存在しない()
    {
        $todo = Todo::factory()->create();

        $params = [
            'title' => '更新タイトル',
            'content' => '更新内容'
        ];

        $res = $this->putJson(route('api.todo.update', $todo->id + 1), $params);

        $res->assertStatus(404);
    }

    /**
     * @test
     */
    public function Todoの削除を行う()
    {
        $todo = Todo::factory()->create();

        $res = $this->deleteJson(route('api.todo.destroy', $todo->id));

        $res->assertOk();

        $this->assertNull(Todo::find($todo->id));
    }
}
