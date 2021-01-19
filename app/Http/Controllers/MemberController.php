<?php

namespace App\Http\Controllers;

use App\AOD\Traits\Procedureable;
use App\Http\Requests\DeleteMember;
use App\Models\Division;
use App\Models\Handle;
use App\Models\Member;
use App\Models\MemberHistory;
use App\Models\Platoon;
use App\Models\Position;
use App\Models\Rank;
use App\Repositories\MemberRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\View\View;

/**
 * Class MemberController
 *
 * @package App\Http\Controllers
 */
class MemberController extends Controller
{
    use Procedureable;

    /**
     * @var MemberRepository
     */
    protected $member;

    /**
     * MemberController constructor.
     *
     * @param  MemberRepository  $member
     */
    public function __construct(MemberRepository $member)
    {
        $this->member = $member;

        $this->middleware('auth');
    }

    /**
     * Search for a member
     *
     * @param $name
     * @return Factory|View
     * @internal param $name
     */
    public function search($name = null)
    {
        if (!$name) {
            $name = request()->name;
        }

        if ($name) {
            $members = Member::where('name', 'LIKE', "%{$name}%")
                ->with('rank', 'division')->get();
        } else {
            $members = [];
        }

        if (request()->ajax()) {
            return view('member.search-ajax', compact('members'));
        }

        return view('member.search', compact('members'));
    }

    /**
     * Endpoint for Bootcomplete
     *
     * @param  Request  $request
     * @return mixed
     */
    public function searchAutoComplete(Request $request)
    {
        $query = $request->input('query');

        $members = Member::where('name', 'LIKE', "%{$query}%")->take(5)->get();

        return $members->map(fn($member) => [
            'id' => $member->clan_id,
            'label' => $member->name
        ]);
    }

    public function editHandles(Member $member)
    {
        $this->authorize('manageIngameHandles', $member);

        $handles = $this->getHandles($member);

        $division = $member->division;

        return view('member.manage-ingame-handles', compact('handles', 'member', 'division'));
    }

    /**
     * @param  Member  $member
     * @return Collection
     */
    private function getHandles(Member $member)
    {
        $handles = Handle::all()->map(function ($handle) use ($member) {
            $newHandle = [
                'id' => $handle->id,
                'label' => $handle->label,
                'type' => $handle->type,
                'comments' => $handle->comments,
                'enabled' => false,
            ];

            if ($member->handles->contains($handle->id)) {
                $newHandle['enabled'] = true;
                $newHandle['value'] = $member->handles->filter(fn($myHandle
                ) => $handle->type === $myHandle->type)->first()->pivot->value;
            }

            return $newHandle;
        });

        return $handles->sortBy('type')->values();
    }

    public function editPartTime(Member $member)
    {
        $this->authorize('managePartTime', $member);

        $division = $member->division;

        /**
         * omit divisions the member is already part-time in
         * omit member's primary division from list of available divisions
         **/
        $divisions = Division::active()->get()->except(
            $member->partTimeDivisions->pluck('id')->toArray()
        )->filter(function ($division) use ($member) {
            if ($member->division) {
                return $division->id !== $member->division->id;
            }

            return $division;
        });

        return view('member.manage-part-time', compact('member', 'division', 'divisions'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Member  $member
     * @return \Illuminate\Contracts\Foundation\Application|Factory|\Illuminate\Contracts\View\View|Response
     * @internal param int $id
     */
    public function show(Member $member)
    {
        $division = $member->division;

        // hide admin notes from non-admin users
        $notes = $member->notes()->with('author')->get()
            ->filter(function ($note) {
                if ($note->type == 'sr_ldr') {
                    return auth()->user()->isRole(['sr_ldr', 'admin']);
                }

                return true;
            });

        $member->load('recruits', 'recruits.division', 'recruits.rank');

        $rankHistory = MemberHistory::query()->with([
            'trackable' => function (MorphTo $morphTo) {
                $morphTo->morphWith([Rank::class]);
            },
        ])->orderByDesc('created_at')->get();

        $lastRankChange = ($rankHistory->count())
            ? $rankHistory->first()->created_at->format('Y-m-d')
            : 'Never';

        $ranks = (auth()->user()->isRole('admin'))
            ? Rank::all()
            : Rank::where('id', '<', auth()->user()->member->rank_id)->get();

        $partTimeDivisions = $member->partTimeDivisions()
            ->whereActive(true)
            ->get();

        return view('member.show', compact(
            'member',
            'division',
            'notes',
            'partTimeDivisions',
            'rankHistory',
            'lastRankChange',
            'ranks'
        ));
    }

    /**
     * Assigns a position to the given member
     *
     * @param  Request  $request
     * @throws AuthorizationException
     */
    public function updatePosition(Request $request)
    {
        $member = Member::find($request->member);
        $this->authorize('update', $member);
        $member->assignPosition(Position::find($request->position));
        $member->save();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Member  $member
     * @return \Illuminate\Contracts\Foundation\Application|Factory|\Illuminate\Contracts\View\View|Response
     * @throws AuthorizationException
     */
    public function edit(Member $member)
    {
        $this->authorize('update', $member);
        $division = $member->division;
        $positions = Position::all()->pluck('id', 'name');
        $ranks = Rank::all()->pluck('id', 'name');

        return view('member.edit-member', compact('member', 'division', 'positions', 'ranks'));
    }

    /**
     * Sync player handles
     *
     * @param  Request  $request
     */
    public function updateHandles(Request $request)
    {
        $member = Member::find($request->member_id);
        $handles = [];

        foreach ($request->handles as $handle) {
            $handles[$handle['id']] = [
                'value' => $handle['value']
            ];
        }

        $member->handles()->sync($handles);
        $this->showToast('Member handles have been updated!');
    }

    /**
     * Remove member from AOD
     *
     * @param  Member  $member
     * @param  DeleteMember  $form
     * @return Response
     */
    public function destroy(Member $member, DeleteMember $form)
    {
        $division = $member->division;

        $form->persist();

        $this->showToast(
            ucwords($member->name ?? 'Member') . " has been removed."
        );

        return redirect()->route('division', [
            $division->abbreviation
        ]);
    }

    public function assignPlatoon($member)
    {
        $platoon = Platoon::find(request()->platoon_id);
        $member->platoon_id = $platoon->id;
        $member->save();
    }

    public function confirmUnassign($member)
    {
        $this->authorize('reset', $member);

        $division = $member->division;

        return view('member.confirm-unassign', compact('member', 'division'));
    }

    /**
     * @param $member
     * @return RedirectResponse
     */
    public function unassignMember($member)
    {
        $member->squad_id = 0;
        $member->platoon_id = 0;
        $member->save();

        $this->showToast('Member assignments reset successfully');

        return redirect()->route('member', $member->getUrlParams());
    }

    public function recruitingHistory($member)
    {
        $division = $member->division;

        return view('member.recruits', compact('member', 'division'));
    }
}
