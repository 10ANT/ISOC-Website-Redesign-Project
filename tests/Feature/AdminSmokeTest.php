<?php

namespace Tests\Feature;

use App\Enums\UserStatus;
use App\Models\Announcement;
use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\Media;
use App\Models\Page;
use App\Models\Person;
use App\Models\Post;
use App\Models\Programme;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Renders every admin page (index, create, edit, show) to catch broken views.
 */
class AdminSmokeTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->admin = User::where('email', 'admin@isocjm.org')->firstOrFail();


        ContactMessage::create([
            'name' => 'Jane Brown',
            'email' => 'jane@example.com',
            'phone' => '+1 876 555 0100',
            'enquiry_type' => 'membership',
            'subject' => 'Joining the chapter',
            'message' => "Hello,\nHow do I join?",
        ]);
    }

    public function test_admin_index_and_create_pages_render(): void
    {
        $urls = [
            route('admin.dashboard'),
            route('admin.posts.index'),
            route('admin.posts.index', ['status' => 'draft', 'q' => 'a']),
            route('admin.posts.create'),
            route('admin.events.index'),
            route('admin.events.index', ['when' => 'past']),
            route('admin.events.index', ['q' => 'a']),
            route('admin.events.create'),
            route('admin.resources.index'),
            route('admin.resources.index', ['type' => 'report']),
            route('admin.resources.create'),
            route('admin.programmes.index'),
            route('admin.programmes.create'),
            route('admin.pages.index'),
            route('admin.pages.create'),
            route('admin.announcements.index'),
            route('admin.announcements.create'),
            route('admin.categories.index'),
            route('admin.people.index'),
            route('admin.people.index', ['group' => 'board']),
            route('admin.people.create'),
            route('admin.people.create', ['group' => 'officer']),
            route('admin.messages.index'),
            route('admin.messages.index', ['type' => 'membership', 'unread' => 1]),
            route('admin.media.index'),
            route('admin.settings.edit'),
            route('admin.users.index'),
            route('admin.users.index', ['status' => 'pending', 'role' => 'member', 'q' => 'a']),
        ];

        foreach ($urls as $url) {
            $this->actingAs($this->admin)->get($url)->assertOk();
        }
    }

    public function test_admin_edit_pages_render(): void
    {
        $pending = User::where('status', UserStatus::Pending)->firstOrFail();

        $urls = [
            route('admin.posts.edit', Post::firstOrFail()),
            route('admin.events.edit', Event::firstOrFail()),
            route('admin.resources.edit', Resource::firstOrFail()),
            route('admin.programmes.edit', Programme::firstOrFail()),
            route('admin.pages.edit', Page::where('slug', 'privacy-policy')->first() ?? Page::firstOrFail()),
            route('admin.pages.edit', Page::firstOrFail()),
            route('admin.announcements.edit', Announcement::firstOrFail()),
            route('admin.people.edit', Person::firstOrFail()),
            route('admin.users.edit', $this->admin),
            route('admin.users.edit', $pending),
            route('admin.messages.show', ContactMessage::firstOrFail()),
        ];

        foreach ($urls as $url) {
            $this->actingAs($this->admin)->get($url)->assertOk();
        }
    }

    public function test_dashboard_shows_pending_members_with_approve_action(): void
    {
        $pending = User::where('status', UserStatus::Pending)->firstOrFail();

        $this->actingAs($this->admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee($pending->name)
            ->assertSee(route('admin.users.approve', $pending), false);
    }

    public function test_editor_dashboard_hides_user_management(): void
    {
        $editor = User::where('email', 'editor@isocjm.org')->firstOrFail();

        $this->actingAs($editor)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee(route('admin.users.index'), false);
    }

    public function test_reading_a_message_marks_it_read(): void
    {
        $message = ContactMessage::firstOrFail();

        $this->actingAs($this->admin)->get(route('admin.messages.show', $message))
            ->assertOk()
            ->assertSee('mailto:jane@example.com', false);

        $this->assertNotNull($message->fresh()->read_at);
    }

    public function test_media_library_and_resource_files_render(): void
    {
        foreach ([['library/photo.jpg', 'image/jpeg'], ['library/flyer.pdf', 'application/pdf']] as [$path, $mime]) {
            Media::create([
                'user_id' => $this->admin->id,
                'path' => $path,
                'original_name' => basename($path),
                'mime_type' => $mime,
                'size' => 204800,
            ]);
        }

        $resource = Resource::firstOrFail();
        $resource->forceFill([
            'file_path' => 'resources/report.pdf',
            'file_name' => 'report.pdf',
            'file_size' => 512000,
        ])->save();

        $this->actingAs($this->admin)->get(route('admin.media.index'))
            ->assertOk()
            ->assertSee('photo.jpg')
            ->assertSee('data-copy=', false);

        $this->actingAs($this->admin)->get(route('admin.resources.index'))->assertOk();

        $this->actingAs($this->admin)->get(route('admin.resources.edit', $resource))
            ->assertOk()
            ->assertSee('report.pdf')
            ->assertSee('name="remove_file"', false);
    }
}
