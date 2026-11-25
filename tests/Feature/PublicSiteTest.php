<?php

namespace Tests\Feature;

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

    public function test_leaders_only_downloads_require_a_leader(): void
    {
        $resource = Resource::where('is_members_only', true)->whereNotNull('file_path')->firstOrFail();

        $this->get(route('resources.download', $resource))->assertRedirect(route('login'));

        $member = User::factory()->create();
        $this->actingAs($member)->get(route('resources.download', $resource))->assertRedirect(route('login'));

        $editor = User::factory()->editor()->create();
        $this->actingAs($editor)->get(route('resources.download', $resource))->assertOk()->assertDownload($resource->file_name);
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

    public function test_request_types_are_accepted_and_preselected(): void
    {
        foreach (['meeting', 'event_request', 'support', 'project'] as $type) {
            $this->get(route('contact', ['type' => $type]))->assertOk()
                ->assertSee('value="'.$type.'" selected', false);

            $this->post(route('contact.store'), [
                'name' => 'Jane Brown', 'email' => 'jane@example.com',
                'enquiry_type' => $type, 'message' => 'We would like to work with the Chapter.',
            ])->assertSessionHas('success');
        }

        $this->assertSame(4, ContactMessage::count());
    }

    public function test_there_is_no_public_sign_up_or_member_portal(): void
    {
        $this->get('/register')->assertNotFound();
        $this->get('/portal')->assertNotFound();

        $this->get(route('membership'))->assertOk()
            ->assertSee('Join via ISOC')
            ->assertSee('https://www.internetsociety.org/become-a-member/', false);
    }

    public function test_only_leaders_can_sign_in(): void
    {
        $this->post(route('login'), ['email' => 'admin@isocjm.org', 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));
        $this->post(route('logout'));

        $this->post(route('login'), ['email' => 'editor@isocjm.org', 'password' => 'password'])
            ->assertRedirect(route('admin.dashboard'));
        $this->post(route('logout'));

        $member = User::factory()->create();
        $this->post(route('login'), ['email' => $member->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->post(route('login'), ['email' => 'admin@isocjm.org', 'password' => 'wrong'])
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
