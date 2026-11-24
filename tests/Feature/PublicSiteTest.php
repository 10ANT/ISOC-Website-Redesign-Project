<?php

namespace Tests\Feature;

use App\Enums\UserStatus;
use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\Page;
use App\Models\Post;
use App\Models\Programme;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicSiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');
        $this->seed();
    }

    public function test_public_pages_render(): void
    {
        $urls = [
            route('home'),
            route('about'),
            route('about.leadership'),
            route('membership'),
            route('get-involved'),
            route('contact'),
            route('privacy'),
            route('programmes.index'),
            route('events.index'),
            route('events.index', ['when' => 'past', 'q' => 'Internet']),
            route('news.index'),
            route('news.index', ['tag' => 'phishing']),
            route('resources.index'),
            route('resources.index', ['type' => 'guide', 'q' => 'community']),
            route('search', ['q' => 'security']),
            route('search', ['q' => 'zzzz-no-results']),
            route('login'),
            route('register'),
            route('password.request'),
        ];

        Page::all()->each(fn ($page) => $urls[] = route('pages.show', $page));
        Programme::all()->each(fn ($programme) => $urls[] = route('programmes.show', $programme));
        Event::all()->each(fn ($event) => $urls[] = route('events.show', $event));
        Post::all()->each(fn ($post) => $urls[] = route('news.show', $post));
        Resource::all()->each(fn ($resource) => $urls[] = route('resources.show', $resource));

        foreach ($urls as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_home_promotes_the_next_upcoming_event_automatically(): void
    {
        $next = Event::published()->upcoming()->firstWhere('is_featured', true);

        $this->get(route('home'))->assertOk()->assertSee($next->title);
    }

    public function test_unpublished_content_is_hidden(): void
    {
        $event = Event::first();
        $event->update(['is_published' => false]);
        $this->get(route('events.show', $event))->assertNotFound();

        $post = Post::first();
        $post->update(['published_at' => null]);
        $this->get(route('news.show', $post))->assertNotFound();
    }

    public function test_members_only_downloads_require_an_approved_member(): void
    {
        $resource = Resource::where('is_members_only', true)->whereNotNull('file_path')->firstOrFail();

        $this->get(route('resources.download', $resource))->assertRedirect(route('login'));

        $pending = User::factory()->pending()->create();
        $this->actingAs($pending)->get(route('resources.download', $resource))->assertRedirect(route('login'));

        $member = User::factory()->create();
        $this->actingAs($member)->get(route('resources.download', $resource))->assertOk()->assertDownload($resource->file_name);
        $this->assertSame(1, $resource->fresh()->downloads);
    }

    public function test_contact_form_stores_message_and_rejects_bots(): void
    {
        $this->post(route('contact.store'), [
            'name' => 'Jane Brown',
            'email' => 'jane@example.com',
            'enquiry_type' => 'membership',
            'message' => 'How do I join?',
        ])->assertRedirect()->assertSessionHas('success');

        $this->post(route('contact.store'), [
            'name' => 'Bot', 'email' => 'bot@example.com', 'enquiry_type' => 'general',
            'message' => 'spam', 'website' => 'http://spam.example',
        ])->assertRedirect();

        $this->assertSame(1, ContactMessage::count());

        $this->post(route('contact.store'), ['name' => '', 'email' => 'nope', 'enquiry_type' => 'x', 'message' => ''])
            ->assertSessionHasErrors(['name', 'email', 'enquiry_type', 'message']);
    }

    public function test_registration_creates_a_pending_member(): void
    {
        $this->post(route('register'), [
            'name' => 'New Member',
            'email' => 'new@example.com',
            'stakeholder_group' => 'Student',
            'parish' => 'St. Ann',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
            'terms' => '1',
        ])->assertRedirect(route('portal.dashboard'));

        $user = User::where('email', 'new@example.com')->firstOrFail();
        $this->assertSame(UserStatus::Pending, $user->status);
        $this->assertAuthenticatedAs($user);

        $this->get(route('portal.dashboard'))->assertOk()->assertSee('awaiting approval');
        $this->get(route('portal.announcements.index'))->assertForbidden();
    }

    public function test_approved_member_portal(): void
    {
        $member = User::factory()->create();

        $this->actingAs($member)->get(route('portal.dashboard'))->assertOk()->assertSee('Welcome to the new member portal');
        $this->get(route('portal.announcements.index'))->assertOk();
        $this->get(route('portal.profile.edit'))->assertOk();

        $this->put(route('portal.profile.update'), ['name' => 'Updated Name', 'email' => $member->email])
            ->assertSessionHas('success');
        $this->assertSame('Updated Name', $member->fresh()->name);
    }

    public function test_login_and_role_based_redirects(): void
    {
        $this->post(route('login'), ['email' => 'admin@isocjm.org', 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));
        $this->post(route('logout'));

        $this->post(route('login'), ['email' => 'member@isocjm.org', 'password' => 'password'])
            ->assertRedirect(route('portal.dashboard'));
        $this->post(route('logout'));

        $this->post(route('login'), ['email' => 'member@isocjm.org', 'password' => 'wrong'])
            ->assertSessionHasErrors('email');
    }

    public function test_suspended_users_cannot_sign_in(): void
    {
        $user = User::factory()->suspended()->create();

        $this->post(route('login'), ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_members_and_guests_cannot_reach_admin(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create())->get(route('admin.dashboard'))->assertForbidden();

        $this->actingAs(User::factory()->editor()->create())->get(route('admin.users.index'))->assertForbidden();
    }
}
