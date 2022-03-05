<?php

namespace Tests\Feature\Api;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyControllerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function 会社情報の登録を行うことができる()
    {
        $params = [
            'name' => '社名',
            'name_kana' => 'しゃめい',
            'post_code' => '333-2222',
            'prefecture' => '東京都',
            'address' => '千代田区1-1',
            'tel' => '09012345678',
            'representative_first_name' => '会社代表者の姓',
            'representative_last_name' => '会社代表者の名',
            'representative_first_name_kana' => 'かいしゃだいひょうせいかな',
            'representative_last_name_kana' => 'かいしゃだいひょうめいかな',
        ];

        $res = $this->postJson(route('api.company.create'), $params);

        $res->assertStatus(201);

        $companies = Company::all();

        $this->assertCount(1, $companies);
        $company = $companies->first();
        $this->assertTrue(collect($params)->every(function ($v, $k) use ($company) {
            return $company->$k === $v;
        }));
    }

    /**
     * @test
     */
    public function 会社情報登録がvalidationでひっかかる()
    {
       $params = [
            'name' => null,
            'name_kana' => 'しゃめい',
            'post_code' => '333-2222',
            'prefecture' => '東京都',
            'address' => '千代田区1-1',
            'tel' => '09012345678',
            'representative_first_name' => '会社代表者の姓',
            'representative_last_name' => '会社代表者の名',
            'representative_first_name_kana' => 'かいしゃだいひょうせいかな',
            'representative_last_name_kana' => 'かいしゃだいひょうめいかな',
        ];

        $res = $this->postJson(route('api.company.create'), $params);

        $res->assertStatus(422);
    }

    /**
     * @test
     */
    public function 会社情報の取得()
    {
        $company = Company::factory()->create([
            'name' => '詳細'
        ]);

        $res = $this->getJson(route('api.company.show', $company->id));

        $res->assertOk();

        $data = $res->json();

        $this->assertSame($company->name, $data['name']);
    }

    /**
     * @test
     */
    public function 会社情報が存在しない()
    {
        $company = Company::factory()->create();

        $res = $this->getJson(route('api.company.show', $company->id + 1));

        $res->assertStatus(404);
    }

    /**
     * @test
     */
    public function 会社情報を更新する()
    {
        $company = Company::factory()->create();
        $params = [
            'name' => '更新',
            'name_kana' => 'こうしん',
            'post_code' => '333-2222',
            'prefecture' => '東京都',
            'address' => '千代田区1-1',
            'tel' => '09012345679',
            'representative_first_name' => '会社代表者の姓更新',
            'representative_last_name' => '会社代表者の名更新',
            'representative_first_name_kana' => 'かいしゃだいひょうせいかなこうしん',
            'representative_last_name_kana' => 'かいしゃだいひょうめいかなこうしん',
        ];
        $res = $this->putJson(route('api.company.update', $company->id), $params);

        $res->assertOk();

        $data = $res->json();

        $this->assertTrue(collect($params)->every(function ($v, $k) use ($data) {
            return $data[$k] === $v;
        }));
    }

     /**
     * @test
     */
    public function 会社情報を更新でvalidationにひっかかる()
    {
        $company = Company::factory()->create();
        $params = [
            'name' => null,
            'name_kana' => 'こうしん',
            'post_code' => '333-2222',
            'prefecture' => '東京都',
            'address' => '千代田区1-1',
            'tel' => '09012345679',
            'representative_first_name' => '会社代表者の姓更新',
            'representative_last_name' => '会社代表者の名更新',
            'representative_first_name_kana' => 'かいしゃだいひょうせいかなこうしん',
            'representative_last_name_kana' => 'かいしゃだいひょうめいかなこうしん',
        ];
        $res = $this->putJson(route('api.company.update', $company->id), $params);

        $res->assertStatus(422);
    }

    /**
     * @test
     */
    public function 会社情報を削除する()
    {
        $company = Company::factory()->create();

        $res = $this->deleteJson(route('api.company.delete', $company->id));

        $res->assertOk();

        $this->assertCount(0, Company::all());
    }

    /**
     * @test
     */
    public function 会社情報を削除際、データが存在しない()
    {
        $company = Company::factory()->create();

        $res = $this->deleteJson(route('api.company.delete', $company->id + 1));

        $res->assertStatus(404);
    }
}
