<?php

namespace App\Http\Controllers;



use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Workbasket;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\WorkbasketRequest;
use App\Http\Resources\WorkbasketResources;
use App\Http\Collection\WorkbasketCollection;

class WorkbasketController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        // $user_id = Auth::user()->id;

        // $limit = $request->limit ? $request->limit : 15;

        // $data = Workbasket::where('handle_by', $user_id)->paginate($limit);

        // return new WorkbasketCollection($data);

        $user = Auth::user(); // Get the currently logged-in user
        $limit = $request->limit ?? 15;


        $query = Workbasket::query();

        if ($user->roles->contains('id', 3)) {

            $query->where('status', '1');
        } else {

            $query->where('handle_by', $user->id);
        }


        $data = $query->paginate($limit);


        return new WorkbasketCollection($data);
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
