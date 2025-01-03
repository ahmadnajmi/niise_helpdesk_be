<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use App\Models\ActionCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ActionCodeController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if(isset($request->perPage)) {
            $perPage = $request->perPage;
        } else {
            $perPage = 10;
        }

        $actionCodes = ActionCode::orderBy('ac_status_rec')->paginate($perPage);

        return $this->success('Success', $actionCodes);
    }

    /**
     * Search function for action codes.
     */
    public function search(Request $request)
    {
        $searchTerm = $request->input('search'); // The term to search for
        $query = DB::table('refAction'); // Replace with your table name

        // Get all column names for the table
        $columns = Schema::getColumnListing('refAction');

        // Loop through columns and apply the search term
        $query->where(function ($q) use ($columns, $searchTerm) {
            foreach ($columns as $column) {
                $q->orWhere($column, 'LIKE', "%{$searchTerm}%");
            }
        });

        $results = $query->paginate($request->input("perPage"));

        return $this->success('Success',  $results);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
