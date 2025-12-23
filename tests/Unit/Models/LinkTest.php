<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test que un enlace puede ser creado con datos válidos
     */
    public function test_link_can_be_created(): void
    {
        $user = User::factory()->create();

        $link = Link::create([
            'title' => 'Mi YouTube',
            'url' => 'https://www.youtube.com/channel/micanal',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('links', [
            'title' => 'Mi YouTube',
            'url' => 'https://www.youtube.com/channel/micanal',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test que un enlace pertenece a un usuario
     */
    public function test_link_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $link = Link::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $link->user);
        $this->assertEquals($user->id, $link->user->id);
    }

    /**
     * Test que un usuario tiene muchos enlaces
     */
    public function test_user_can_have_multiple_links(): void
    {
        $user = User::factory()->create();

        Link::factory()->create([
            'title' => 'YouTube',
            'user_id' => $user->id,
        ]);

        Link::factory()->create([
            'title' => 'Instagram',
            'user_id' => $user->id,
        ]);

        Link::factory()->create([
            'title' => 'Twitch',
            'user_id' => $user->id,
        ]);

        $this->assertCount(3, $user->links);
    }

    /**
     * Test que los enlaces pueden ser actualizados
     */
    public function test_link_can_be_updated(): void
    {
        $link = Link::factory()->create([
            'title' => 'Antiguo título',
            'url' => 'https://example.com/old',
        ]);

        $link->update([
            'title' => 'Nuevo título',
            'url' => 'https://example.com/new',
        ]);

        $this->assertEquals('Nuevo título', $link->title);
        $this->assertEquals('https://example.com/new', $link->url);
    }

    /**
     * Test que un enlace puede ser eliminado
     */
    public function test_link_can_be_deleted(): void
    {
        $link = Link::factory()->create();
        $linkId = $link->id;

        $link->delete();

        $this->assertDatabaseMissing('links', ['id' => $linkId]);
    }

    /**
     * Test que los enlaces se cargan ordenados por fecha reciente
     */
    public function test_links_are_ordered_by_latest_created(): void
    {
        $user = User::factory()->create();

        $link1 = Link::factory()->create([
            'title' => 'Primer enlace',
            'user_id' => $user->id,
            'created_at' => now()->subDays(2),
        ]);

        $link2 = Link::factory()->create([
            'title' => 'Segundo enlace',
            'user_id' => $user->id,
            'created_at' => now(),
        ]);

        $link3 = Link::factory()->create([
            'title' => 'Tercer enlace',
            'user_id' => $user->id,
            'created_at' => now()->subDay(),
        ]);

        $links = $user->links()->latest()->get();

        // El más reciente debe ser primero
        $this->assertEquals('Segundo enlace', $links[0]->title);
        $this->assertEquals('Tercer enlace', $links[1]->title);
        $this->assertEquals('Primer enlace', $links[2]->title);
    }

    /**
     * Test que los campos requeridos no pueden ser nulos
     */
    public function test_link_requires_mandatory_fields(): void
    {
        $user = User::factory()->create();

        $this->expectException(\Illuminate\Database\QueryException::class);

        Link::create([
            'user_id' => $user->id,
            // Falta 'title' y 'url' que son requeridos
        ]);
    }

    /**
     * Test que el título no puede estar vacío
     */
    public function test_link_title_cannot_be_empty(): void
    {
        $user = User::factory()->create();

        $this->expectException(\Illuminate\Database\QueryException::class);

        Link::create([
            'title' => null,
            'url' => 'https://example.com',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test que la URL no puede estar vacía
     */
    public function test_link_url_cannot_be_empty(): void
    {
        $user = User::factory()->create();

        $this->expectException(\Illuminate\Database\QueryException::class);

        Link::create([
            'title' => 'Mi enlace',
            'url' => null,
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test que el título tiene límite de caracteres
     */
    public function test_link_title_has_character_limit(): void
    {
        $user = User::factory()->create();

        // Un título muy largo (más de 255 caracteres)
        $longTitle = str_repeat('a', 256);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Link::create([
            'title' => $longTitle,
            'url' => 'https://example.com',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test que los enlaces se pueden buscar por usuario
     */
    public function test_can_retrieve_links_by_user(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        Link::factory()->count(3)->create(['user_id' => $user1->id]);
        Link::factory()->count(2)->create(['user_id' => $user2->id]);

        $user1Links = $user1->links;
        $user2Links = $user2->links;

        $this->assertCount(3, $user1Links);
        $this->assertCount(2, $user2Links);
    }

    /**
     * Test que los campos fillable se asignan correctamente
     */
    public function test_link_fillable_fields(): void
    {
        $user = User::factory()->create();

        $data = [
            'title' => 'Nuevo enlace',
            'url' => 'https://example.com',
            'user_id' => $user->id,
        ];

        $link = Link::create($data);

        $this->assertEquals('Nuevo enlace', $link->title);
        $this->assertEquals('https://example.com', $link->url);
        $this->assertEquals($user->id, $link->user_id);
    }
}
