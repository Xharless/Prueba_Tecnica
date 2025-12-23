<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Link;
use App\Models\Support;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que un usuario puede ser creado con datos válidos
     */
    public function test_user_can_be_created(): void
    {
        $user = User::create([
            'name' => 'Carlos García',
            'username' => 'carlosgarcia',
            'email' => 'carlos@example.com',
            'password' => bcrypt('password123'),
            'bio' => 'Soy un creador de contenido',
            'avatar' => '/storage/avatars/default.jpg',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'carlos@example.com',
            'username' => 'carlosgarcia',
        ]);

        $this->assertEquals('Carlos García', $user->name);
        $this->assertEquals('carlosgarcia', $user->username);
    }

    /**
     * Test que un usuario tiene muchos enlaces
     */
    public function test_user_has_many_links(): void
    {
        $user = User::factory()->create();

        // Crear 3 enlaces para este usuario
        Link::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertCount(3, $user->links);
        $this->assertInstanceOf(Link::class, $user->links->first());
    }

    /**
     * Test que un usuario puede recibir múltiples apoyos
     */
    public function test_user_has_many_supports(): void
    {
        $user = User::factory()->create();

        // Crear 5 apoyos para este usuario
        Support::factory()->count(5)->create(['user_id' => $user->id]);

        $this->assertCount(5, $user->supports);
        $this->assertInstanceOf(Support::class, $user->supports->first());
    }

    /**
     * Test que los apoyos se ordenan por los más recientes primero
     */
    public function test_user_supports_are_ordered_by_latest(): void
    {
        $user = User::factory()->create();

        $support1 = Support::factory()->create([
            'user_id' => $user->id,
            'supporter_name' => 'Primer apoyo',
            'created_at' => now()->subDays(2),
        ]);

        $support2 = Support::factory()->create([
            'user_id' => $user->id,
            'supporter_name' => 'Segundo apoyo',
            'created_at' => now(),
        ]);

        $support3 = Support::factory()->create([
            'user_id' => $user->id,
            'supporter_name' => 'Tercer apoyo',
            'created_at' => now()->subDay(),
        ]);

        $supports = $user->supports()->get();

        // El más reciente debe ser primero
        $this->assertEquals('Segundo apoyo', $supports[0]->supporter_name);
        $this->assertEquals('Tercer apoyo', $supports[1]->supporter_name);
        $this->assertEquals('Primer apoyo', $supports[2]->supporter_name);
    }

    /**
     * Test que el username es único
     */
    public function test_user_username_must_be_unique(): void
    {
        User::create([
            'name' => 'Usuario Uno',
            'username' => 'usuario_unico',
            'email' => 'uno@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        User::create([
            'name' => 'Usuario Dos',
            'username' => 'usuario_unico', // Username duplicado
            'email' => 'dos@example.com',
            'password' => bcrypt('password123'),
        ]);
    }

    /**
     * Test que el email es único
     */
    public function test_user_email_must_be_unique(): void
    {
        User::create([
            'name' => 'Usuario Uno',
            'username' => 'usuario_uno',
            'email' => 'repetido@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        User::create([
            'name' => 'Usuario Dos',
            'username' => 'usuario_dos',
            'email' => 'repetido@example.com', // Email duplicado
            'password' => bcrypt('password123'),
        ]);
    }

    /**
     * Test que la contraseña se almacena hasheada
     */
    public function test_user_password_is_hashed(): void
    {
        $plainPassword = 'micontraseña123';

        $user = User::create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => bcrypt($plainPassword),
        ]);

        // La contraseña no debe ser igual a la original (debe estar hasheada)
        $this->assertNotEquals($plainPassword, $user->password);

        // Pero debe verificarse correctamente
        $this->assertTrue(\Hash::check($plainPassword, $user->password));
    }

    /**
     * Test que los campos requeridos no pueden ser nulos
     */
    public function test_user_requires_mandatory_fields(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        User::create([
            'username' => 'solousername',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            // Falta 'name' que es requerido
        ]);
    }

    /**
     * Test que un usuario puede tener datos opcionales vacíos
     */
    public function test_user_can_have_empty_optional_fields(): void
    {
        $user = User::create([
            'name' => 'Carlos',
            'username' => 'carlos',
            'email' => 'carlos@example.com',
            'password' => bcrypt('password123'),
            'bio' => null,
            'avatar' => null,
        ]);

        $this->assertNull($user->bio);
        $this->assertNull($user->avatar);
    }

    /**
     * Test que un usuario puede ser eliminado
     */
    public function test_user_can_be_deleted(): void
    {
        $user = User::factory()->create();
        $userId = $user->id;

        $user->delete();

        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    /**
     * Test que al eliminar un usuario se eliminan sus enlaces
     */
    public function test_deleting_user_deletes_related_links(): void
    {
        $user = User::factory()->create();
        Link::factory()->count(3)->create(['user_id' => $user->id]);

        $user->delete();

        $this->assertDatabaseMissing('links', ['user_id' => $user->id]);
    }

    /**
     * Test que al eliminar un usuario se eliminan sus apoyos recibidos
     */
    public function test_deleting_user_deletes_related_supports(): void
    {
        $user = User::factory()->create();
        Support::factory()->count(3)->create(['user_id' => $user->id]);

        $user->delete();

        $this->assertDatabaseMissing('supports', ['user_id' => $user->id]);
    }
}
