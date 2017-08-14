<?php

namespace App\Http\Controllers\Users;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Enterprise;
use Illuminate\Support\Facades\DB;
use App\UsersAndController;

class UsersAndControllersController extends Controller
{
    public function showList($namespace)
    {
        $ent_id = $this->shareEnterpriseToView($namespace);
        $u_and_c = DB::table('users_and_controllers')
            ->where('users_and_controllers.enterprise_id', $ent_id)
            ->join('users', 'users.id', '=', 'users_and_controllers.user_id')
            ->join('controllers', 'controllers.id', '=', 'users_and_controllers.controller_id')
            ->join('modules', 'modules.id', '=', 'controllers.module_id')
            ->select(
                'controllers.table as table',
                'controllers.fields as fields',
                'users.first_name as first_name',
                'users.last_name as last_name',
                'users.login as login',
                'modules.name as module',
                'controllers.name as controller',
                'users_and_controllers.id as id',
                'users_and_controllers.item_id as item_id'
            )->get();

        foreach ($u_and_c as &$item) {
            $fields = explode(', ', $item->fields);
            $item->fields = $fields;
            if ($item->table == 'users') {
                $item->item_name = User::getSimpleUserById($item->item_id, $ent_id);
            } else {
                $item->item_name = DB::table($item->table)->where("enterprise_id", $ent_id)
                    ->where('id', $item->item_id)
                    ->select($fields)
                    ->get();
            }
        }
        return view('usersAndContr.show', compact('u_and_c'));
    }

    public function edit($namespace, $id, Request $request)
    {
        $ent_id = $this->shareEnterpriseToView($namespace);
        $user_and_c = UsersAndController::where('enterprise_id', $ent_id)->where('id', $id)->first();
        $current_item_id = $user_and_c->item_id;
        $user = User::getSimpleUserById($user_and_c->user_id, $ent_id);
        $controller = $this->getController($user_and_c->controller_id);
        $fields = explode(', ', $controller->fields);
        if ($request->isMethod('post')) {
            $user_and_c->item_id = $request->item_id;
            $user_and_c->save();
            return back();
        }

        if ($controller->table == 'users') {
            $table_items = User::getAllSimpleUsers($ent_id, $fields);
        } else {
            $table_items = DB::table($controller->table)
                ->where('enterprise_id', $ent_id)
                ->select($fields)
                ->get();
        }
        return view('usersAndContr.edit', compact('user', 'controller', 'table_items', 'current_item_id', 'fields'));
    }

    public function create($namespace, Request $request)
    {
        $ent_id = $this->shareEnterpriseToView($namespace);
        if ($request->isMethod('post')) {
            $user = User::getSimpleUserById($request->user_id, $ent_id);
            $controller = $this->getController($request->controller_id);
            if ($request->has('next')) {
                $fields = explode(', ', $controller->fields);
                if ($controller->table == 'users') {
                    $table_items = User::getAllSimpleUsers($ent_id, $fields);
                } else {
                    $table_items = DB::table($controller->table)
                        ->where('enterprise_id', $ent_id)
                        ->select($fields)
                        ->get();
                }
                return view('usersAndContr.finish', compact('user', 'controller', 'table_items', 'fields'));
            }
            if ($request->has('create')) {
                $item_id = DB::table($controller->table)
                    ->where('enterprise_id', $ent_id)
                    ->where('id', $request->item_id)
                    ->value('id');
                if ($item_id) {
                    $users_and_controllers = new UsersAndController();
                    $users_and_controllers->enterprise_id = $ent_id;
                    $users_and_controllers->user_id = $user->id;
                    $users_and_controllers->controller_id = $controller->id;
                    $users_and_controllers->item_id = $item_id;
                    $users_and_controllers->save();
                }
                return redirect(config('ems.prefix') . "{$namespace}/Users/UsersAndControllers/showList");
            }
        } else {
            $users = User::getAllSimpleUsers($ent_id);
            $controllers = DB::table('controllers')->where('controllers.table', '<>', 'null')
                ->where('controllers.is_active', 1)
                ->join('modules', 'modules.id', '=', 'controllers.module_id')->where('modules.is_active', 1)
                ->select('modules.name as module', 'controllers.name as controller', 'controllers.id as id')
                ->get();

            return view('usersAndContr.create', compact('users', 'controllers'));
        }
    }

    public function delete($namespace, $id)
    {
        $u_a_c = UsersAndController::findOrFail($id);
        $u_a_c->delete();
        return back();
    }

    private function getController($id)
    {
        $controller = DB::table('controllers')->where('controllers.table', '<>', 'null')
            ->where('controllers.id', $id)
            ->where('controllers.is_active', 1)
            ->join('modules', 'modules.id', '=', 'controllers.module_id')->where('modules.is_active', 1)
            ->select(
                'modules.name as module',
                'controllers.name as controller',
                'controllers.id as id',
                'controllers.table as table',
                'controllers.fields as fields'
            )
            ->first();
        return $controller;
    }

    private function shareEnterpriseToView($namespace)
    {
        $enterprise = Enterprise::where('namespace', $namespace)->firstOrFail();
        view()->share('enterprise', $enterprise);
        return $enterprise->id;
    }
}
