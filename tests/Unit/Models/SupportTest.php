<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Support;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que un apoyo puede ser creado con datos válidos
     */
    public function test_support_can_be_created(): void
    {
        $user = User::factory()->create();

        $support = Support::create([
            'user_id' => $user->id,
            'supporter_name' => 'Juan Pérez',
            'message' => '¡Tu contenido es increíble!',
            'amount' => 5000,
        ]);

        $this->assertDatabaseHas('supports', [
            'user_id' => $user->id,
            'supporter_name' => 'Juan Pérez',
            'amount' => 5000,
        ]);
    }

    /**
     * Test que un apoyo pertenece a un usuario
     */
    public function test_support_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $support = Support::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $support->user);
        $this->assertEquals($user->id, $support->user->id);
    }

    /**
     * Test que un usuario puede recibir múltiples apoyos
     */
    public function test_user_can_receive_multiple_supports(): void
    {
        $user = User::factory()->create();

        Support::factory()->create([
            'user_id' => $user->id,
            'supporter_name' => 'Ana',
            'amount' => 1000,
        ]);

        Support::factory()->create([
            'user_id' => $user->id,
            'supporter_name' => 'Carlos',
            'amount' => 2000,
        ]);

        Support::factory()->create([
            'user_id' => $user->id,
            'supporter_name' => 'María',
            'amount' => 5000,
        ]);

        $this->assertCount(3, $user->supports);
    }

    /**
     * Test que un apoyo puede ser actualizado
     */
    public function test_support_can_be_updated(): void
    {
        $support = Support::factory()->create([
            'supporter_name' => 'Nombre anterior',
            'message' => 'Mensaje anterior',
            'amount' => 1000,
        ]);

        $support->update([
            'supporter_name' => 'Nuevo nombre',
            'message' => 'Nuevo mensaje',
            'amount' => 2000,
        ]);

        $this->assertEquals('Nuevo nombre', $support->supporter_name);
        $this->assertEquals('Nuevo mensaje', $support->message);
        $this->assertEquals(2000, $support->amount);
    }

    /**
     * Test que un apoyo puede ser eliminado
     */
    public function test_support_can_be_deleted(): void
    {
        $support = Support::factory()->create();
        $supportId = $support->id;

        $support->delete();

        $this->assertDatabaseMissing('supports', ['id' => $supportId]);
    }

    /**
     * Test que el nombre del apoyo es requerido
     */
    public function test_support_requires_supporter_name(): void
    {
        $user = User::factory()->create();

        $this->expectException(\Illuminate\Database\QueryException::class);

        Support::create([
            'user_id' => $user->id,
            'supporter_name' => null,
            'amount' => 5000,
        ]);
    }

    /**
     * Test que el monto es requerido
     */
    public function test_support_requires_amount(): void
    {
        $user = User::factory()->create();

        $this->expectException(\Illuminate\Database\QueryException::class);

        Support::create([
            'user_id' => $user->id,
            'supporter_name' => 'Juan',
            'amount' => null,
        ]);
    }

    /**
     * Test que el mensaje es opcional
     */
    public function test_support_message_is_optional(): void
    {
        $user = User::factory()->create();

        $support = Support::create([
            'user_id' => $user->id,
            'supporter_name' => 'Ana',
            'message' => null,
            'amount' => 1000,
        ]);

        $this->assertNull($support->message);
    }

    /**
     * Test que el nombre del apoyo no puede tener más de 255 caracteres
     */
    public function test_support_name_has_character_limit(): void
    {
        $user = User::factory()->create();
        $longName = str_repeat('a', 256);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Support::create([
            'user_id' => $user->id,
            'supporter_name' => $longName,
            'amount' => 5000,
        ]);
    }

    /**
     * Test que el mensaje no puede tener más de 500 caracteres
     */
    public function test_support_message_has_character_limit(): void
    {
        $user = User::factory()->create();
        $longMessage = str_repeat('a', 501);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Support::create([
            'user_id' => $user->id,
            'supporter_name' => 'Juan',
            'message' => $longMessage,
            'amount' => 5000,
        ]);
    }

    /**
     * Test que el monto debe ser un número válido
     */
    public function test_support_amount_must_be_numeric(): void
    {
        $user = User::factory()->create();

        // Esto debería funcionar si la BD lo permite
        $support = Support::create([
            'user_id' => $user->id,
            'supporter_name' => 'Juan',
            'amount' => 1000.50,
        ]);

        $this->assertIsNumeric($support->amount);
    }

    /**
     * Test que se pueden obtener apoyos ordenados por fecha
     */
    public function test_supports_can_be_ordered_by_date(): void
    {
        $user = User::factory()->create();

        $support1 = Support::factory()->create([
            'user_id' => $user->id,
            'supporter_name' => 'Ana',
            'created_at' => now()->subDays(5),
        ]);

        $support2 = Support::factory()->create([
            'user_id' => $user->id,
            'supporter_name' => 'Carlos',
            'created_at' => now(),
        ]);

        $support3 = Support::factory()->create([
            'user_id' => $user->id,
            'supporter_name' => 'María',
            'created_at' => now()->subDays(2),
        ]);

        $supports = Support::where('user_id', $user->id)->latest()->get();

        $this->assertEquals('Carlos', $supports[0]->supporter_name);
        $this->assertEquals('María', $supports[1]->supporter_name);
        $this->assertEquals('Ana', $supports[2]->supporter_name);
    }

    /**
     * Test que se puede filtrar apoyos por usuario
     */
    public function test_can_filter_supports_by_user(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Support::factory()->count(4)->create(['user_id' => $user1->id]);
        Support::factory()->count(3)->create(['user_id' => $user2->id]);

        $user1Supports = Support::where('user_id', $user1->id)->get();
        $user2Supports = Support::where('user_id', $user2->id)->get();

        $this->assertCount(4, $user1Supports);
        $this->assertCount(3, $user2Supports);
    }

    /**
     * Test que los campos fillable se asignan correctamente
     */
    public function test_support_fillable_fields(): void
    {
        $user = User::factory()->create();

        $data = [
            'user_id' => $user->id,
            'supporter_name' => 'Juan Pérez',
            'message' => 'Excelente trabajo',
            'amount' => 5000,
        ];

        $support = Support::create($data);

        $this->assertEquals('Juan Pérez', $support->supporter_name);
        $this->assertEquals('Excelente trabajo', $support->message);
        $this->assertEquals(5000, $support->amount);
    }

    /**
     * Test que se pueden calcular estadísticas de apoyos
     */
    public function test_can_calculate_support_statistics(): void
    {
        $user = User::factory()->create();

        Support::factory()->create(['user_id' => $user->id, 'amount' => 1000]);
        Support::factory()->create(['user_id' => $user->id, 'amount' => 2000]);
        Support::factory()->create(['user_id' => $user->id, 'amount' => 5000]);

        $totalAmount = Support::where('user_id', $user->id)->sum('amount');
        $count = Support::where('user_id', $user->id)->count();

        $this->assertEquals(8000, $totalAmount);
        $this->assertEquals(3, $count);
    }
}
