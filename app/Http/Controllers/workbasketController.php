<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Workbasket;
use App\Http\Traits\ResponseTrait;
use App\Http\Collection\WorkbasketCollection;
use App\Http\Resources\WorkbasketResources;
use App\Http\Requests\WorkbasketRequest;

class workbasketController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        // $limit = $request->limit ? $request->limit : 15;

        // $data =  Workbasket::paginate($limit);

        // return new WorkbasketCollection($data);

        $workbasket = [
            (object)[
                'id' => 1,
                'date' => '1/11/2025',
                'category' => 'LAN',
                'title' => 'Server Down',
                'severity' => 'high',
                'status' => 'new',
                'assignee' => null,
            ],
            (object)[
                'id' => 2,
                'date' => '2/11/2025',
                'category' => 'Authentication',
                'title' => 'Email Not Working',
                'severity' => 'medium',
                'status' => 'in_progress',
                'assignee' => (object)['name' => 'John Doe'],
            ],
            (object)[
                'id' => 3,
                'date' => '3/11/2025',
                'category' => 'Web',
                'title' => 'Website Slow',
                'severity' => 'low',
                'status' => 'closed',
                'assignee' => (object)['name' => 'Jane Smith'],
            ],
        ];

        return view('workbasket.index', compact('workbasket'));
    }

    public function store(WorkbasketRequest $request)
    {
        try {
            $data = $request->all();

            $create = Workbasket::create($data);

            $data = new WorkbasketResources($create);

            return $this->success('Success', $data);

        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function show(Workbasket $workbasket)
    {
        $data = new WorkbasketResources($workbasket);

        return $this->success('Success', $data);
    }

    public function update(WorkbasketRequest $request, Workbasket $workbasket)
    {
        try {
            $data = $request->all();

            $update = $workbasket->update($data);

            $data = new WorkbasketResources($workbasket);

            return $this->success('Success', $data);

        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function destroy(Workbasket $workbasket)
    {
        $workbasket->delete();

        return $this->success('Success', null);
    }
}
