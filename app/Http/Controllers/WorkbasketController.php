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

        $user = Auth::user();
        $limit = $request->limit ?? 15;

        $frontlinerId = Role::FRONTLINER;
        $statusNew = Workbasket::NEW;

        $query = Workbasket::query();

        if ($user->roles->contains('id', $frontlinerId)) {

            $query->where('status', $statusNew);
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
