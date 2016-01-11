<?php
namespace Minhbang\AccessControl\Requests;

use Minhbang\LaravelKit\Extensions\Request;

/**
 * Class RoleRequest
 *
 * @package Minhbang\AccessControl\Requests
 */
class RoleRequest extends Request
{
    public $trans_prefix = 'access-control::role';
    public $rules = [
        'system_name'  => 'required|max:128|alpha_dash|unique:roles',
        'full_name'    => 'required|max:128',
        'short_name'   => 'required|max:60',
        'acronym_name' => 'required|max:20',
        'level'        => 'integer',
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        /** @var \Minhbang\AccessControl\Models\Role $role */
        if ($role = $this->route('role')) {
            //update Role
            $this->rules['system_name'] .= ',system_name,' . $role->id;
        } else {
            //create Role
        }
        return $this->rules;
    }

}
