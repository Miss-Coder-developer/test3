<?php

namespace App\Http\Controllers;

use App\Models\FireBaseModel;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $folders = [
            ['name' => 'folder1', 'total' => 100, 'confirmed' => 10, 'full_link'=>'#'],
            ['name' => 'folder2', 'total' => 150, 'confirmed' => 10, 'full_link'=>'#'],
            ['name' => 'folder3', 'total' => 200, 'confirmed' => 200, 'full_link'=>'#'],
        ];

        $model = new FireBaseModel();
        $model->initialize();
        $folders = $model->getAllFoldersByName();
        $type = 'name';
        return view('folders', compact('folders', 'type'));
    }

    public function indexByDate()
    {
        $model = new FireBaseModel();
        $model->initialize();
        $folders = $model->getAllFoldersByDate();
        $type = 'date';
        return view('folders', compact('folders', 'type'));
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
