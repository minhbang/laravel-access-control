<?php
namespace Minhbang\AccessControl\Requests;

use Minhbang\LaravelKit\Extensions\Request;

/**
 * Class RoleGroupRequest
 *
 * @package Minhbang\AccessControl\Requests
 */
class RoleGroupRequest extends Request
{
    public $trans_prefix = 'access-control::role_group';
    public $rules = [
        'name'  => 'required|max:100|unique:role_groups',
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
        /** @var \Minhbang\AccessControl\Models\RoleGroup $role_group */
        if ($role_group = $this->route('role_group')) {
            //update RoleGroup
            $this->rules['name'] .= ',name,' . $role_group->id;
        } else {
            //create RoleGroup
        }
        return $this->rules;
    }

}
