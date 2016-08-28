<?php

namespace Renatio\Todos\Models;

use Backend\Facades\Backend;
use Backend\Facades\BackendAuth;
use Backend\Models\User;
use Model;
use October\Rain\Database\Traits\Sortable;
use October\Rain\Database\Traits\Validation;

/**
 * Class TodoList
 * @package Renatio\Todos\Models
 */
class TodoList extends Model
{

    use Validation;
    use Sortable;

    /**
     * @var string
     */
    public $table = 'renatio_todos_lists';

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @var array
     */
    public $attributeNames = [
        'name' => 'renatio.todos::lang.field.name',
    ];

    /**
     * @var array
     */
    public $rules = [
        'name' => 'required|max:255',
    ];

    /**
     * @var array
     */
    public $hasMany = [
        'completed_tasks' => [
            Task::class,
            'key' => 'list_id',
            'scope' => 'completed',
            'delete' => true,
        ],
        'open_tasks' => [
            Task::class,
            'key' => 'list_id',
            'scope' => 'open',
            'delete' => true,
        ],
        'open_tasks_count' => [
            Task::class,
            'count' => 'true',
            'key' => 'list_id',
            'conditions' => 'completed_at is null',
        ],
    ];

    /**
     * @var array
     */
    public $belongsTo = [
        'user' => User::class,
    ];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeForCurrentUser($query)
    {
        return $query->where('user_id', BackendAuth::getUser()->id);
    }

    /**
     * @return mixed
     */
    public function getUrlAttribute()
    {
        return Backend::url('renatio/todos/lists/update/' . $this->id);
    }

}