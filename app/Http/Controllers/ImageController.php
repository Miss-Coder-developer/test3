<?php

namespace App\Http\Controllers;

use App\Models\FireBaseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{
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
    public function show(Request $request)
    {
        //
        $imageList=null;
        if($request['type']=='name'){
            $imageList = $this->getImageData($request['id']);
        }else{
            $imageList = $this->getImageDataByDate($request['id']);
        }

        $size = count($imageList);

        return view('images', compact('imageList', 'size'));

    }

    private function getMockData(){
        return [
            ['name'=> 'image1', 'url'=> 'https://picsum.photos/500', 'id'=>'123456', 'confirmed'=>'true', 'approved'=>'false'],
            ['name'=> 'image2', 'url'=> 'https://picsum.photos/400', 'id'=>'12345', 'confirmed'=>'false', 'approved'=>'false'],
            ['name'=> 'image3', 'url'=> 'https://picsum.photos/600', 'id'=>'123456', 'confirmed'=>'true', 'approved'=>'true'],

        ];
    }

    private function getImageData($prefix){
        $model = new FireBaseModel();
        $model->initialize();
        $images = $model->getAllImagesByFolderName($prefix);
        return $images;

    }

    private function getImageDataByDate($date){
        $model = new FireBaseModel();
        $model->initialize();
        $images = $model->getAllImagesByDate($date);
        return $images;

    }

    public function confirm(Request $request){
        Log::debug('entering confirm');
        $model = new FireBaseModel();
        $model->initialize();
        Log::debug($request['id']);
        $model->confirmImage($request['id'], 'confirmed');
        return response("success", Response::HTTP_OK);
    }

    public function tested(Request $request){
        Log::debug('entering approve');
        $model = new FireBaseModel();
        $model->initialize();
        Log::debug($request['id']);
        $model->confirmImage($request['id'], 'approved');
        return response("success", Response::HTTP_OK);
    }

    public function delete(Request $request){
        Log::debug('entering delete');
        $model = new FireBaseModel();
        $model->initialize();
        Log::debug($request['id']);
        $model->confirmImage($request['id'], 'delete');
        return response("success", Response::HTTP_OK);
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
