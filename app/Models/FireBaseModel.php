<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;


class FireBaseModel extends Model
{
    use HasFactory;

    public $factory;
    public $auth;
    public $cloudStorage;
    public $firestore;
    public $initialized;

    public function initialize(){
        $factory = (new Factory)
            ->withServiceAccount(storage_path('insightbim-86877-97906250c885.json'))
            ->withDatabaseUri('https://insightbim-86877.firebaseio.com');

        $this->auth = $factory->createAuth();
        $this->cloudStorage = $factory->createStorage();
        $this->firestore = $factory->createFirestore();
        $this->initialized = true;
        return true;
    }

    public function getAllImages($prefix){
        $database = $this->firestore->database();
        $collection = $database->collection('CameraAppImages');
        $query = $collection->where('ScaffoldComponent', '=', $prefix);
        $results = $query->documents();
        $images = [];
        foreach($results as $result ){
            if(!$this->getArrayValue($result, 'delete')) {
                $image = ['name' => $result['ID'], 'url' => $result['ImageURL'], 'id' => $result['ID'], 'confirmed' => $this->getArrayValue($result, 'confirmed'), 'approved' => $this->getArrayValue($result, 'approved')];
                if (!in_array($image, $images)) {
                    array_push($images, $image);
                }
            }
        }
        return $images;

    }

    public function getAllFoldersByDate(){
        $startDate = new \DateTime(date('Y-m-d', strtotime('today - 7 days')));
        $endDate = new \DateTime(date('Y-m-d', strtotime('tomorrow')));
        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($startDate, $interval, $endDate);
        $dates = [];
        foreach ($period as $dt) {
            $subfolder = ['name'=> $dt->format("Y-m-d"), 'full_link'=> $dt->format("Y-m-d")];
            array_push($dates, $subfolder);
        }

        foreach ($dates as $key=>$date){
            $totals = $this->getTotalImageCount($date['name']);
            $dates[$key]['total']=$totals['total'];
            $dates[$key]['deleted']=$totals['deleted'];
            $dates[$key]['approved']=$totals['approved'];
            $dates[$key]['confirmed']=$totals['confirmed'];
        }

        return $dates;
    }



    private function getTotalImageCount($folder){
        $database = $this->firestore->database();
        $collection = $database->collection('CameraAppImages');
            $datestamp = date('Y-m-d', strtotime($folder . '+1 day'));
            $query = $collection->where('DateTime', '>=', $folder)->where('DateTime', '<=', $datestamp)->orderBy('DateTime', 'DESC');
        $all = $query->documents();
        $rows = $all->rows();

        $deleted = array_filter($rows, array($this,"filterDeleted"));
        $approved = array_filter($rows, array($this,"filterApproved"));
        $confirmed = array_filter($rows, array($this,"filterConfirmed"));

        $results = [];
        $results['total'] = $all->size();
        $results['deleted']=count($deleted);
        $results['approved']=count($approved);;
        $results['confirmed']=count($confirmed);

        return($results);
    }


    function filterDeleted($var){
        if(isset($var['delete']) && $var['delete']=='true'){
            return true;
        }else{
            return false;
        }
    }

    function filterApproved($var){
        if(isset($var['approved']) && $var['approved']=='true'){
            return true;
        }else{
            return false;
        }
    }

    function filterConfirmed($var){
        if(isset($var['confirmed']) && $var['confirmed']=='true'){
            return true;
        }else{
            return false;
        }
    }

    private function getTotalImageCountFolders($folder){
        $database = $this->firestore->database();
        $collection = $database->collection('CameraAppImages');
        $query = $collection->where('ScaffoldComponent', '=', $folder);
        $all = $query->documents();
        $rows = $all->rows();
        $deleted = array_filter($rows, array($this,"filterDeleted"));
        $approved = array_filter($rows, array($this,"filterApproved"));
        $confirmed = array_filter($rows, array($this,"filterConfirmed"));

        $results = [];
        $results['total'] = $all->size();
        $results['deleted']=count($deleted);
        $results['approved']=count($approved);;
        $results['confirmed']=count($confirmed);


        return($results);
    }



    public function getAllFoldersByName(){
        if($this->initialized){

            $defaultBucket = $this->cloudStorage->getBucket();
            $objects = $defaultBucket->objects(
                [
                    'prefix' => 'originalImages/'
                ]
            );

            $folders = [];

            foreach ($objects as $object) {
                $subfolder = ['name'=> explode('/', $object->name())[1], 'full_link'=>explode('/', $object->name())[1]];
                if(!in_array($subfolder, $folders)){
                    array_push($folders, $subfolder);
                }
            }

            foreach ($folders as $key=>$date){
                $totals = $this->getTotalImageCountFolders($date['name']);
                $folders[$key]['total']=$totals['total'];
                $folders[$key]['deleted']=$totals['deleted'];
                $folders[$key]['approved']=$totals['approved'];
                $folders[$key]['confirmed']=$totals['confirmed'];
            }

            return $folders;

        }else{
            return null;
        }

    }

    public function getAllImagesByFolderName($prefix){
        $database = $this->firestore->database();
        $collection = $database->collection('CameraAppImages');
        $query = $collection->where('ScaffoldComponent', '=', $prefix)->orderBy('DateTime', 'DESC');
        $results = $query->documents();
        $images = [];
        foreach($results as $result ){
            if(!$this->getArrayValue($result, 'delete')) {
                $image = $this->getImage($result);
                if (!in_array($image, $images)) {
                    array_push($images, $image);
                }
            }
        }
        return $images;

    }

    private function getImage($result){
        return  ['name' => $result['ID'], 'url' => $this->getArrayValue($result, 'ImageURL'), 'id' => $result['ID'],
            'confirmed' => $this->getArrayValue($result, 'confirmed'), 'approved' => $this->getArrayValue($result, 'approved'),
            'scaffoldComponent' => $result['ScaffoldComponent'],'appVersion' => $this->getArrayValue($result, 'AppVersion'),
            'scaffoldCount'=>$result['ScaffoldCount'], 'date'=>$result['DateTime'],
            'deleted' => $this->getArrayValue($result, 'delete')];
    }

    public function getAllImagesByDate($date){
        $database = $this->firestore->database();
        $collection = $database->collection('CameraAppImages');
        $datestamp = date('Y-m-d', strtotime($date. '+1 day'));
        $query = $collection->where('DateTime', '>=', $date)->where('DateTime', '<=', $datestamp)->orderBy('DateTime', 'DESC');
        $results = $query->documents();

        $images = [];
        foreach($results as $result ){
            if(!$this->getArrayValue($result, 'delete')) {
                $image = $this->getImage($result);
                if (!in_array($image, $images)) {
                    array_push($images, $image);
                }
            }
        }
        return $images;
    }


    private function getArrayValue($array, $key){
    try{
            return $array[$key];
    }catch(\ErrorException $ex) {
        return false;
    }
    }

    public function confirmImage($id, $path){
        $database = $this->firestore->database();
        $collection = $database->collection('CameraAppImages');
        $document = $collection->document($id);
        $result = $document->update([
            ['path' => $path, 'value' => 'true'],
        ]);
        return true;
    }



}
