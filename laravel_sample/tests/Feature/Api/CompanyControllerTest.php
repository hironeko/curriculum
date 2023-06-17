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
        $params = $this->params();

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
       $params = $this->params();
       $params['name'] = null;

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
        $params =$this->params();

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
        $params = $this->params();
        $params['name'] = null;
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

    /**
     * @test
     */
    public function 会社情報と請求先情報を同時登録する()
    {
        $params = $this->params();
        $params['billing'] = $this->billingParams();

        $res = $this->postJson(route('api.company.create.with_billing'), $params);

        $res->assertStatus(201);
        $data = $res->json();
        $this->assertTrue(collect($this->params())->every(function($v, $k) use ($data) {
            return $data[$k] === $v;
        }));
        $this->assertTrue(collect($this->billingParams())->every(function($v, $k) use ($data) {
            return $data['billing'][$k] === $v;
        }));
    }

    /**
     * @test
     */
    public function 会社情報と請求先情報を同時登録する際にvalidationエラーになる()
    {
        $params = $this->params();
        $params['billing'] = $this->billingParams();

        $params['name'] = null;

        $res = $this->postJson(route('api.company.create.with_billing'), $params);

        $res->assertStatus(422);
    }

    private function params()
    {
        return [
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
    }

    private function billingParams()
    {
        return [
            'name' => '請求先社名',
            'name_kana' => 'せいきゅうさきしゃめい',
            'post_code' => '333-2222',
            'prefecture' => '東京都',
            'address' => '千代田区1-1',
            'tel' => '09012345678',
            'department' => '宛先部署',
            'billing_first_name' => '会社代表者の姓',
            'billing_last_name' => '会社代表者の名',
            'billing_first_name_kana' => 'かいしゃだいひょうせいかな',
            'billing_last_name_kana' => 'かいしゃだいひょうめいかな',
        ];
    }
}
