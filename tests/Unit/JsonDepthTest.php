<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Str;
use Tests\TestCase;

class JsonDepthTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic test example.
     */
    public function test_json_depth_for_database(): void
    {
        $key = Str::lower(Str::random(4));

        $depth = 37;

        $data = [Str::random(4)];

        while ($depth--) {
            $data[$key] = [$data];
        }

        $this->assertGreaterThan(65, $this->array_depth($data));

        $user = new User();

        $user->data = $data;

        $this->assertTrue($user->save());

        $user->refresh();

        $this->assertEquals($data, $user->data);
    }

    protected function array_depth(array $array)
    {
        $max_depth = 1;

        foreach ($array as $value) {
            if (is_array($value)) {
                $depth = $this->array_depth($value) + 1;

                if ($depth > $max_depth) {
                    $max_depth = $depth;
                }
            }
        }

        return $max_depth;
    }
}
