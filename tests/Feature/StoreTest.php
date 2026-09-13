<?php
namespace Tests\Feature; use Tests\TestCase; use Illuminate\Foundation\Testing\RefreshDatabase; use App\Models\{Product,Category};
class StoreTest extends TestCase {use RefreshDatabase; public function test_store_home_loads():void{$c=Category::create(['name'=>'Action','slug'=>'action']);Product::create(['category_id'=>$c->id,'name'=>'Demo Game','slug'=>'demo-game','price'=>10,'platform'=>'PC','region'=>'Global','active'=>true]);$this->get('/')->assertOk()->assertSee('Demo Game');}}
