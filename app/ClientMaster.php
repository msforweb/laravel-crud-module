<?php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class ClientMaster extends Model
{
    public static $Instance;

    public static function obj()
    {
        if(!is_object(self::$Instance))
            self::$Instance = new self();

        return self::$Instance;
    }

    use Notifiable;

    protected  $table        = 'client_master';
    protected  $primaryKey   = 'cm_id';
    public     $field_prefix = 'cm_';
    protected  $fillable     = ['cm_firstname','cm_lastname','cm_photo','cm_email','cm_phone','cm_notes','cm_am_id','cm_is_active','cm_type','cm_created_at','cm_updated_at'];
    public     $timestamps   =  false;
    protected  $dateFormat   =  'U';
    public     $V_Upload     =   '/agents/clients';

    # Set rules for the input form.
    public static $rulesOnAddClients = [
        'cm_firstname' => 'required',
        'cm_lastname'  => 'required',
        'cm_email'     => 'required',
        'cm_phone'     => 'required',
        'cm_photo'     => 'image|mimes:jpeg,png,jpg|max:2048',
    ];

    # Set messages for the the inputs error
    public static $messages = [
        'cm_firstname.required' => 'Please enter firstname',
        'cm_lastname.required'  => 'Please enter lastname',
        'cm_email.required'     => 'Please enter valid email address',
        'cm_phone.required'     => 'Please enter valid phone no',
        'cm_photo.required'     => 'Please enter valid photo',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    /** Get client info from the email id
     * @param $email
     * @return bool|mixed
     */
    public function getInfoByEmailId($email)
    {
        if($email == '')
            return false;

        return $this->where('cm_email',$email)->first();
    }

    /** Get client all client info based on the $request. If not pass then return all data with default so and sd otherwise return data based on that.
     * @param $request
     * @return mixed
     */
    public function getAllClientInfo($request){

    	$sort_order = ($request->get('so') != '')?$request->get('so'):$this->primaryKey;
        $sort_dir   = ($request->get('sd') != '')?$request->get('sd'):'ASC';
        $limit      = ($request->post('limit') > 0)?$request->post('limit'):config('constants.PAGE_SIZE');

        return $this->leftjoin('client_property_info', 'client_master.cm_id', '=', 'client_property_info.cpi_cm_id')
	        ->where('cm_am_id',Auth::user()->Agent[AgentMaster::obj()->primaryKey])
            ->orderBy($sort_order,$sort_dir)
            ->paginate($limit);
    }

    /** Get client information by client id
     * @param $id
     * @return false|mixed
     */
    public function getClientInfoById($id){

        if(!is_numeric($id))
            return false;

        return  $this->where('client_master.'.$this->primaryKey,$id)
                    ->leftjoin('client_property_info', 'client_master.cm_id', '=', 'client_property_info.cpi_cm_id')
                    ->first();
    }

    /** Upload file on the server
     * @param $FileName
     * @return string | false
     */
    public function  uploadFile($FileName){
        global $physical_path;

        if($FileName == '')
            return false;

        $imageName = time().'.'.$FileName->getClientOriginalExtension();
        $FileName->move($physical_path['public_images'].$this->V_Upload, $imageName);
        return $imageName;
    }

    /** Remove file from the server
     * @param $FileName
     * @return string
     */
    public function removeFile($FileName){
        global $physical_path;

        if($FileName == '')
            return false;

        $image_path = $physical_path['public_images'].$this->V_Upload.$FileName;  // Value is not URL but directory file path
        if(file_exists($image_path)) {
           @unlink($image_path);
            return $imageName = "none";
        }
    }

    /** Save client data in the database based on POST
     * @param $POST
     * @return bool|string
     */
    public function Insert($POST){

        # Check client is already exist or not.
        $clientinfo = $this->getInfoByEmailId ($POST['cm_email']);
        if($clientinfo != '')
        {
            return "email is already exist";
        }

        # set post as required
        $image_name = '';
        if(isset($POST['cm_photo']) && $POST['cm_photo'] != '')
            $image_name = $this->uploadFile ($POST['cm_photo']);

        $POST['cm_am_id']       = Auth::user()->Agent[AgentMaster::obj()->primaryKey];
        $POST['cm_photo']       = $image_name;
        $objClientMaster = new ClientMaster();
        $objClientMaster->fill($POST);
        $objClientMaster->save();

        if(!$objClientMaster->save()){
            return false;
        }
        else{
            return $objClientMaster->cm_id;
        }
    }

    /** update client data in the database based on POST
     * @param $POST
     * @param $id
     * @return bool
     */

    public function Updates($POST, $id){
        $image_name = '';
        $remove_rst = '';

        # Check client is already exist or not.
        if(isset($POST['cm_email']) && $POST['cm_email'] != '')
        {
            $clientinfo = $this->getInfoByEmailId ($POST['cm_email']);
            if($clientinfo != '')
            {
                unset($POST['cm_email']);
            }
        }
        if(isset($POST['rm_cm_photo']) && isset($POST['hd_cm_photo'])&& $POST['rm_cm_photo'] == true && $POST['hd_cm_photo'] != '' )
        {
                $remove_rst = $this->removeFile($POST['hd_cm_photo']);
                $POST['cm_photo'] = $remove_rst;
        }

        if(isset($POST['cm_photo']) && $POST['cm_photo'] != '')
        {
            $image_name = $this->uploadFile ($POST['cm_photo']);
            $POST['cm_photo']       = $image_name;
        }

        # Update record.
        $objClientMaster = ClientMaster::find($id)->update($POST);

        if(!$objClientMaster){
            return false;
        }
        else{
            return $objClientMaster;
        }

    }

    /** update Active/inactive filed in the database
     * @param $POST
     * @param $id
     * @return bool
     */
    public function isActiveStatus($POST, $id){
        if($POST['cm_is_active'] == 'Yes'){
            $POST['cm_is_active'] = 'No';
        }
        else
        {
            $POST['cm_is_active'] = 'Yes';
        }
        $objClientMaster = $this->Updates($POST,$id);
        if(!$objClientMaster){
            return false;
        }
        else{
            return $objClientMaster;
        }
    }

    /** delet client reocord from the database
     * @param $id
     * @return mixed
     */
    public  function Deletes($id){
        return $objClientMaster = ClientMaster::find($id)->delete();
    }
}