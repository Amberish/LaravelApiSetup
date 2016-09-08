<?php
namespace Helpers;

use \Maatwebsite\Excel\Files\ExcelFile;
use Dingo\Api\Routing\Helpers;

class UserListImport extends ExcelFile {

    private $destination, $filename;

    function setDestination(){
      $this->destination = storage_path('app/public/imports/user_lists');
    }

    function setFilename(){
      $this->filename = time() . '_user_list.xls';
    }

    public function getFile(){
      //Setting destination folder and filename.
      $this->setDestination();
      $this->setFilename();

      //Getting file from the request.
      $request = app('Illuminate\Http\Request');

      if($request->hasFile('user_list')){
        $request->file('user_list')->move($this->destination, $this->filename);

        return $this->destination . '/' . $this->filename;
      }

      abort(404, 'File not found!!');

    }

    public function getFilters(){
       return [
           'chunk'
       ];
    }
}

?>
