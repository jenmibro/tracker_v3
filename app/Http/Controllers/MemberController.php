<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteMember;
use App\Member;
use App\Notifications\MemberRemoved;
use App\Position;
use App\Repositories\MemberRepository;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Toastr;

class MemberController extends Controller
{
    protected $member;

    /**
     * MemberController constructor.
     *
     * @param MemberRepository $member
     */
    public function __construct(MemberRepository $member)
    {
        $this->member = $member;

        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Search for a member
     *
     * @param $name
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @internal param $name
     */
    public function search($name)
    {
        $members = Member::where('name', 'LIKE', "%{$name}%")
            ->with('rank')->get();

        return view('member.search', compact('members', 'request'));
    }

    /**
     * Endpoint for Bootcomplete
     *
     * @param Request $request
     * @return mixed
     */
    public function searchAutoComplete(Request $request)
    {
        $query = $request->input('query');

        $members = Member::where('name', 'LIKE', "%{$query}%")->take(5)->get();

        return $members->map(function ($member) {
            return [
                'id' => $member->clan_id,
                'label' => $member->name
            ];
        });
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Member $member
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show(Member $member)
    {
        $division = $member->primaryDivision;

        // hide admin notes from non-admin users
        $notes = $member->notes()->with('author')->get()
            ->filter(function ($note) {
                if ($note->type == 'sr_ldr') {
                    return auth()->user()->isRole(['sr_ldr', 'admin']);
                }

                return true;
            });

        return view('member.show', compact(
            'member', 'division', 'notes'
        ));
    }

    /**
     * Assigns a position to the given member
     * @param Request $request
     */
    public function updatePosition(Request $request)
    {
        $member = Member::find($request->member);
        $this->authorize('update', $member);
        $member->assignPosition(Position::find($request->position));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Member $member
     * @return \Illuminate\Http\Response
     */
    public function edit(Member $member)
    {
        $this->authorize('update', $member);

        $division = $member->primaryDivision;
        $positions = Position::all()->pluck('name', 'id');

        return view('member.edit-member', compact(
            'member', 'division', 'positions'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove member from AOD
     *
     * @param Member $member
     * @param DeleteMember $form
     * @return Response
     */
    public function destroy(Member $member, DeleteMember $form)
    {
        $division = $member->primaryDivision;

        if ($division->settings()->get('slack_alert_removed_member')) {
            $division->notify(new MemberRemoved($member, $form->input('removal-reason')));
        }

        $form->persist();

        Toastr::success(
            ucwords($member->name) . " has been removed from the {$division->name} Division!",
            "Success",
            [
                'positionClass' => 'toast-top-right',
                'progressBar' => true
            ]
        );

        return redirect()->route('division', [
            $division->abbreviation
        ]);
    }
}
