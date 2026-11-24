<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PersonGroup;
use App\Http\Controllers\Admin\Concerns\StoresImages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PersonRequest;
use App\Models\Person;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PersonController extends Controller
{
    use StoresImages;

    public function index(Request $request): View
    {
        $group = PersonGroup::tryFrom((string) $request->query('group'));

        return view('admin.people.index', [
            'people' => Person::when($group, fn ($q) => $q->where('group', $group))
                ->orderBy('group')->ordered()->paginate(25)->withQueryString(),
            'groups' => PersonGroup::cases(),
            'activeGroup' => $group,
        ]);
    }

    public function create(Request $request): View
    {
        return $this->form(new Person(['group' => PersonGroup::tryFrom((string) $request->query('group')) ?? PersonGroup::Board]));
    }

    public function store(PersonRequest $request): RedirectResponse
    {
        $person = new Person($request->payload());
        $person->photo = $this->handleImage($request, null, 'people', 'photo');
        $person->save();

        return redirect()->route('admin.people.index', ['group' => $person->group->value])->with('success', 'Person added.');
    }

    public function edit(Person $person): View
    {
        return $this->form($person);
    }

    public function update(PersonRequest $request, Person $person): RedirectResponse
    {
        $person->fill($request->payload());
        $person->photo = $this->handleImage($request, $person->photo, 'people', 'photo');
        $person->save();

        return redirect()->route('admin.people.index', ['group' => $person->group->value])->with('success', 'Person updated.');
    }

    public function destroy(Person $person): RedirectResponse
    {
        $this->deleteImage($person->photo);
        $person->delete();

        return back()->with('success', 'Person removed.');
    }

    private function form(Person $person): View
    {
        return view('admin.people.form', ['person' => $person, 'groups' => PersonGroup::cases()]);
    }
}
