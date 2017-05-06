<?php

namespace App\Http\Controllers;

use App\Division;
use App\Repositories\ClanRepository;

class ClanStatisticsController extends Controller
{

    private $clan;

    public function __construct(ClanRepository $clanRepository)
    {
        $this->middleware('auth');

        $this->clan = $clanRepository;
    }

    public function show()
    {
        $memberCount = $this->clan->totalActiveMembers();

        // get our census information, and organize it
        $censusCounts = $this->clan->censusCounts();
        $previousCensus = $censusCounts->first();
        $lastYearCensus = $censusCounts->reverse();

        // fetch all divisions and eager load census data
        $cencuses = Division::active()->with('census')->get()
            // filter out divisions without census information
            ->filter(function ($division) {
                return count($division->census);
            })
            // calculate population and weekly active
            ->each(function ($division) {
                $count = $division->census->last()->count;
                $weeklyActive = $division->census->last()->weekly_active_count;

                $division->total = $count;
                $division->popMinusActive = $count - $weeklyActive;
                $division->weeklyActive = $weeklyActive;
            });

        // break down rank distribution
        $rankDemographic = collect($this->clan->allRankDemographic());
        $rankDemographic->each(function ($rank) use ($memberCount) {
            $rank->difference = $memberCount - $rank->count;
        });

        return view('statistics.show')->with(compact(
            'memberCount', 'previousCensus', 'lastYearCensus', 'memberCount',
            'cencuses', 'rankDemographic'
        ));
    }
}
